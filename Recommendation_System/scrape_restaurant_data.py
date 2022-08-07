# Read the output .csv file from webscraping.py and get each restaurant data
from bs4 import BeautifulSoup
import requests
import pandas as pd
import csv
import json

data = pd.read_csv("popular_restaurants.csv", delimiter=",", encoding="utf-8")		# Doesn't support myanmar font
print(data.columns)
print(type(data.link[0]))
print(data.link[0])

# 2nd(less efficient) method for reading csv data
# with open("popular_restaurants.csv", "r") as f:
# 	csvreader = csv.reader(f)
# 	header = next(csvreader)
# 	name = []
# 	link = []
# 	for i, row in enumerate(csvreader):
# 		name.append(row[0])
# 		link.append(row[1])

with open("restaurant_data" + ".csv", "w") as f:
	f.write("name,link,food,latitude,longitude\n")
	headers = {'User-Agent': 'window.navigator.userAgent'}
	link = '/home/kan/Downloads/html/Conifer46 Menu _ Order Online on foodpanda Myanmar.html'
	soup = BeautifulSoup(open(link, encoding='utf-8'), 'lxml')
	# print(soup)
	restaurant_list_script = soup.find('script', {"data-testid" : "restaurant-seo-schema"})
	# print(restaurant_list_script)
	restaurant_dict = json.loads(restaurant_list_script.text)
	latitude = restaurant_dict['geo']['latitude']
	longitude = restaurant_dict['geo']['longitude']
	food_list = soup.find_all("li", class_="dish-card")
	print(latitude, longitude)
	print(food_list)
	# output = f"{data.name[0]},{data.link[0]},{},{},{}\n"
	# 	f.write(output)