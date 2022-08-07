# Read the output .csv file from webscraping.py and get each restaurant data
from bs4 import BeautifulSoup
import requests
import pandas as pd
import csv
import json

data = pd.read_csv("popular_restaurants.csv", delimiter=",", encoding="utf-8")		# Doesn't support myanmar font
# print(data.columns)
# print(type(data.link[0]))
# print(data.link[0])

# 2nd(less efficient) method for reading csv data
# with open("popular_restaurants.csv", "r") as f:
# 	csvreader = csv.reader(f)
# 	header = next(csvreader)
# 	name = []
# 	link = []
# 	for i, row in enumerate(csvreader):
# 		name.append(row[0])
# 		link.append(row[1])

# Writing data into .json file
with open("missed_restaurants" + ".csv", "w") as m:
	with open("restaurant_data" + ".json", "w") as f:
		restaurant_data = {
			'data': [],
		}
		for i in range(len(data)):
			print("Getting data from ", i, " th restaurant ", data.name[i], "...")
			latitude = None
			longitude = None
			# link = '/home/kan/Important (Back these up)/KMD Projects/Project/Program file/Food_Ordering_System_with_Recommendation/Recommendation_System/selenium_restaurant_html/Conifer46.html'
			# link = '/home/kan/Important (Back these up)/KMD Projects/Project/Program file/Food_Ordering_System_with_Recommendation/Recommendation_System/selenium_restaurant_html/19 Street Shan Noodle.html'
			link = 'selenium_restaurant_html/' + data.name[i] + '.html'
			soup = BeautifulSoup(open(link, encoding='utf-8'), 'lxml')
			title = soup.find('title').text
			if title == "Access to this page has been denied":
				print("Missed getting ", data.name[i])
				output = f"{data.link[i]}\n"
				m.write(output)
			else:
				name = data.name[i]
				link = data.link[i]
				restaurant_list_script = soup.find('script', {"data-testid" : "restaurant-seo-schema"})
				if restaurant_list_script is not None:
				# print(restaurant_list_script)
					restaurant_dict = json.loads(restaurant_list_script.text)
					latitude = restaurant_dict['geo']['latitude']
					longitude = restaurant_dict['geo']['longitude']
				food_list = soup.find_all("li", class_="dish-card")
				# print(food_list)
				restaurant_json = {
					'index': i,
					'name': name,
					'link': link,
					'latitude': latitude,
					'longitude': longitude,
					'food': [],
				}
				for dish_card in food_list:
					product_name = dish_card.find('h3', class_="dish-name").text
					str_price = dish_card.find('span', class_="price p-price").text
					price = int(str_price.replace(',', '').replace('from ', '').replace('MMK ', ''))
					food_dict = {
						'product_name': product_name,
						'price': price,
					}
					restaurant_json['food'].append(food_dict)
				restaurant_data['data'].append(restaurant_json)
					# print(product_name, ",", price)
		f.write(json.dumps(restaurant_data, indent=2))

# # Test reading data from .json file
# with open("restaurant_data" + ".json", "r") as f:
# 	restaurant_data = json.load(f)
# 	print(restaurant_data['data'][2]['food'][2])