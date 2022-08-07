# Read the output .csv file from webscraping.py and get each restaurant data
from bs4 import BeautifulSoup
import requests

headers = {'User-Agent': 'window.navigator.userAgent'}
html_text = requests.get('https://www.foodpanda.com.mm/en/city/yangon', headers=headers).text
soup = BeautifulSoup(html_text, 'lxml')
print(soup)