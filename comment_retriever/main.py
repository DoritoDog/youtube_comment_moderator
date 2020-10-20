"""
Main script to scrape the comments of any Youtube video.
Example:
    $ python main.py YOUTUBE_VIDEO_URL
"""

from selenium import webdriver
from selenium.common import exceptions
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.action_chains import ActionChains
import sys
import time
import json
import requests
from urllib.parse import urlparse
from urllib.parse import parse_qs

# http://localhost:8765/comments/add
def post(url, data):
    x = requests.post(url, data=data, headers={ 'Accept': 'application/json', 'Content-Type': 'application/json'})
    print(x.status_code)
    print(x.text)

def get_comments(driver, url, video_title):
    try:
        # Extract the elements storing the usernames and comments.
        username_elems = driver.find_elements_by_xpath('//a[@id="author-text"]')
        comment_elems = driver.find_elements_by_xpath('//*[@id="content-text"]')
        date_elems = driver.find_elements_by_xpath('//*[@class="published-time-text above-comment style-scope ytd-comment-renderer"]/a')
    except exceptions.NoSuchElementException:
        print('Couldn\'t get a comment or username.')

    sys.stdout.reconfigure(encoding='utf-8')
    comments = []
    for username, comment, date in zip(username_elems, comment_elems, date_elems):
        comment_data = {
            'google_account': {
                'username': username.text,
                'avatar_url': '',
                'channel_url': username.get_attribute('href')
            },
            'video': {
                'youtube_id': parse_qs(urlparse(url).query)['v'][0],
                'url': url,
                'title': video_title
            },
            'text': comment.text,
            'url': date.get_attribute('href'),
            'youtube_id': parse_qs(urlparse(date.get_attribute('href')).query)['lc'][0]
        }
        comments.append(comment_data)
    return comments

def get_replies(driver, wait, time):
    # Get all replies
    replies_buttons = driver.find_elements_by_xpath('//*[@id="more-replies"]/a')
    i = 0
    for button in replies_buttons:
        i = i + 1
        try:
            print('Clicking, {}'.format(i))
            button.click()
        except:
            continue
        
        # try:
        if i >= 2:
            time.sleep(3)
            print('Getting more replies, {}'.format(i))
            more_replies_buttons = driver.find_elements_by_xpath('//*[@class="style-scope yt-next-continuation"]')
            for more_button in more_replies_buttons:
                if more_button.text == 'Show more replies':
                    more_button.click()
            time.sleep(2)
            print('Done for {}'.format(i))
        # except:
        #     print('Exception after getting more replies, {}'.format(i))
        time.sleep(1)

def bypass_login(driver, wait, time):
    # Get rid of the login modal.
    button = wait.until(lambda d: d.find_element_by_xpath('//*[@id="dialog"]/*[@id="button-container"]'))
    button.click()
    time.sleep(1)
    # The "I Agree" button is hidden from CSS selectors, instead use tabs and enter to click on it.
    actions = ActionChains(driver)
    actions.send_keys(Keys.TAB)
    actions.send_keys(Keys.TAB)
    actions.send_keys(Keys.ENTER)
    actions.perform()

def scroll_down(driver):
    driver.execute_script("window.scrollTo(0, document.documentElement.scrollHeight);")

# https://www.youtube.com/watch?v=p9a18OPYkG0
def scrape(url):
    driver = webdriver.Chrome('D:\Downloads\chromedriver\chromedriver.exe')
    wait = WebDriverWait(driver, 10)
    
    driver.get(url)
    time.sleep(2)

    bypass_login(driver, wait, time)

    try:
        # Extract the elements storing the video title and comment section.
        video_title = driver.find_element_by_xpath('//*[@id="container"]/h1/yt-formatted-string').text
        comment_section = driver.find_element_by_xpath('//*[@id="comments"]')
    except exceptions.NoSuchElementException:
        print("Error: Double check selector OR element may not yet be on the screen at the time of the find operation.")

    # Scroll into view the comment section, then allow some time for everything to be loaded as necessary.
    driver.execute_script("arguments[0].scrollIntoView();", comment_section)
    time.sleep(3)
    print('Ready to scrape comments.')

    lastCommentsLen = 0
    while True:
        scroll_down(driver)
        time.sleep(2)

        comments = get_comments(driver, url, video_title)
        get_replies(driver, wait, time)
        new_comments = comments[lastCommentsLen:len(comments)]
        
        print(len(new_comments))
        print(new_comments[0])
        lastCommentsLen = len(comments)

    # comments = get_comments(driver, url, video_title)
    # print(len(comments))
    # data = json.dumps(comments)
    # post('http://localhost:8765/comments/add', data)

    driver.close()

def scrape_new(url):
    driver = webdriver.Chrome('D:\Downloads\chromedriver\chromedriver.exe')
    wait = WebDriverWait(driver, 10)
    
    driver.get(url)
    time.sleep(2)

    bypass_login(driver, wait, time)

    last_comments = []
    while True:
        time.sleep(3)
        try:
            # Extract the elements storing the comment section and video title.
            comment_section = wait.until(lambda d: driver.find_element_by_xpath('//*[@id="comments"]'))
            video_title = driver.find_element_by_xpath('//*[@id="container"]/h1/yt-formatted-string').text
        except exceptions.NoSuchElementException:
            print('Failed to extract the elements storing the video title and comment section.')
        
        # Scroll into view the comment section, then allow some time for everything to be loaded as necessary.
        driver.execute_script("document.getElementById('comments').scrollIntoView();")
        time.sleep(3)

        # Click the sort filter.
        driver.find_elements_by_xpath('//*[@id="label-icon"]')[0].click()
        time.sleep(1)
        # Select "Newest first".
        el = driver.find_elements_by_xpath('//*[@class="item style-scope yt-dropdown-menu"]')[1].click()
        time.sleep(1)

        # Get the newest comments
        comments = get_comments(driver, url, video_title)
        if comments != last_comments:
            print('NEW COMMENTS!')
            post(url, commnts)
        last_comments = comments

        time.sleep(30)
        driver.refresh()

    driver.close()

if __name__ == "__main__":
    scrape(sys.argv[1])
    quit()

    data = json.dumps(comments)
    post('http://localhost:8765/comments/add', data)
