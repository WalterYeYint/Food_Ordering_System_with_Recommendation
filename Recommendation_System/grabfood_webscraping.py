from os import closerange
from bs4 import BeautifulSoup
from pkg_resources import resource_listdir
import requests

i = 1
with open("grab_restaurants" + ".html", "w") as f:
	headers = {'User-Agent': 'window.navigator.userAgent'}
	headers = {'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Safari/537.36'}
	html_text = requests.get('https://food.grab.com/mm/en/restaurant', headers=headers).text
	soup = BeautifulSoup(html_text, 'lxml')
	# print(str(soup))
	f.write(str(soup))