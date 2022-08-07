# Read the output .csv file from webscraping.py and get each restaurant data
from bs4 import BeautifulSoup
import requests
import pandas as pd
import csv
import json
import urllib.request as urlreq
import time
# from pywebcopy import save_webpage

data = pd.read_csv("popular_restaurants.csv", delimiter=",", encoding="utf-8")		# Doesn't support myanmar font
print(data.columns)
print(len(data))
print(data.link[0])

for i in range(len(data)):
	with open("restaurant_html/" + data.name[i] + ".html", "w") as f:
		print("Processing ", i, " th restaurant ", data.name[i], "...")
		headers = {'User-Agent': 'window.navigator.userAgent'}
		html_text = requests.get(data.link[i], headers=headers).text
		soup = BeautifulSoup(html_text, 'lxml')
		f.write(str(soup))
	time.sleep(300)

# headers = {'User-agent' : 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5'}

# from random import seed
# import requests

# with open("abc.html", "w") as f:
# 	url = "https://www.foodpanda.com.mm/restaurant/z0fl/royal-k-restaurant"
# 	session_obj = requests.Session()
# 	headers = {'User-Agent': 'window.navigator.userAgent'}
# 	response = session_obj.get(url, headers=headers)
# 	f.write(response.text)

# headers = {'User-Agent': 'window.navigator.userAgent'}
# for i in range(len(data)):
# 	with open("restaurant_html/" + data.name[i] + ".html", "w") as f:
# 		print("Processing ", i, " th restaurant ", data.name[i], "...")
# 		headers = {'User-Agent': 'window.navigator.userAgent'}
# 		html_text = requests.get(data.link[i], headers=headers).text
# 		soup = BeautifulSoup(html_text, 'mhtml')
# 		food_list = soup.find_all("li", class_="dish-card")
# 		f.write(str(food_list))
# 	time.sleep(300)

# with open("abc.txt", "w") as f:
# 	link = "https://www.foodpanda.com.mm/restaurant/k2fo/conifer46"
# 	headers = {'User-Agent': 'window.navigator.userAgent'}
# 	html_text = requests.get(link, headers=headers).text
# 	soup = BeautifulSoup(html_text, 'lxml')
# 	f.write(str(soup))