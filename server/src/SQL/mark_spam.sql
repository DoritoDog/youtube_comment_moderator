
-- If a user posts 3 identical comments, mark them as spam.
UPDATE comments
INNER JOIN google_accounts ON comments.google_account_id = google_accounts.id
SET comments.status = 'SPAM', comments.updated_at = CURRENT_TIMESTAMP
-- Select all comments posted by this user with the same text.
WHERE (SELECT COUNT(id) FROM (SELECT * FROM comments) AS comments2 WHERE comments.google_account_id = comments2.google_account_id AND comments.text = comments2.text) > (SELECT value FROM settings WHERE name = 'Max. Duplicates')

-- Mark all user comments as spam if their author's username follows a pattern.
UPDATE comments SET comments.status = 'SPAM', comments.updated_at = CURRENT_TIMESTAMP
WHERE (SELECT google_accounts.username FROM google_accounts WHERE google_accounts.id = comments.google_account_id) REGEXP
      (SELECT filters.regex FROM filters WHERE filters.content = 'USERNAME')

-- Mark all user comments as spam if their text follows a pattern.
UPDATE comments SET comments.status = 'SPAM', comments.updated_at = CURRENT_TIMESTAMP
WHERE comments.text REGEXP (SELECT filters.regex FROM filters WHERE filters.content = 'COMMENT')

-- TODO: per video
-- Stop users from copying other's comments.
UPDATE comments SET status = 'SPAM', comments.updated_at = CURRENT_TIMESTAMP
WHERE id = (SELECT id FROM comments
            WHERE comments.text IN (SELECT t1.text FROM (SELECT text FROM comments GROUP BY text HAVING COUNT(`text`) > 1) AS t1)
            -- Newest first (to avoid marking the original as spam).
            ORDER BY id DESC LIMIT 1)
