# Read the output .csv file from webscraping.py and get each restaurant data
import re
from bs4 import BeautifulSoup
import requests
import pandas as pd
import csv
import random as rand
import json

data = pd.read_csv("popular_restaurants.csv", delimiter=",", encoding="utf-8")
name_data = pd.read_csv("adjusted-name-combinations-list.csv")

# Writing data into .json file
with open("missed_data_for_db" + ".csv", "w") as m:
	with open("food_ordering_db" + ".sql", "w") as f:
		create_restaurant = """CREATE TABLE `restaurant` (
													`restaurantID` int(11) NOT NULL,
													`restaurantName` varchar(50) NOT NULL,
													`latitude` float(20),
													`longitude` float(20)
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n"""
		f.write(create_restaurant)
		insert_into_restaurant = """INSERT INTO `restaurant` (`restaurantID`, `restaurantName`, `latitude`, `longitude`) VALUES"""

		create_food = """CREATE TABLE `food` (
													`foodID` int(11) NOT NULL,
													`restaurantID` int(11) NOT NULL,
													`foodName` varchar(50) NOT NULL,
													`price` int(20)
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n"""
		f.write(create_food)
		insert_into_food = """INSERT INTO `food` (`foodID`, `restaurantID`, `foodName`, `price`) VALUES"""

		create_user = """CREATE TABLE `user` (
													`userID` int(11) NOT NULL,
													`firstName` varchar(20) NOT NULL,
													`lastName` varchar(20)
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n"""
		f.write(create_user)
		insert_into_user = """INSERT INTO `user` (`userID`, `firstName`, `lastName`) VALUES"""

		create_cart = """CREATE TABLE `cart` (
													`cartID` int(11) NOT NULL,
													`userID` int(11) NOT NULL,
													`restaurantID` int(11) NOT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n"""
		f.write(create_cart)
		insert_into_cart = """INSERT INTO `cart` (`cartID`, `userID`, `restaurantID`) VALUES"""

		create_order = """CREATE TABLE `order` (
													`orderID` int(11) NOT NULL,
													`foodID` int(11) NOT NULL,
													`cartID` int(11) NOT NULL,
													`rating` int(3) NOT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n"""
		f.write(create_order)
		insert_into_order = """INSERT INTO `order` (`orderID`, `foodID`, `cartID`, `rating`) VALUES"""


		food_id = 1
		restaurant_id = 1
		restaurant_food_dict = {}
		cart_restaurant_dict = {}


		for i in range(len(data)):
			print("Getting data from ", i, " th restaurant ", data.name[i], "...")
			latitude = "NULL"
			longitude = "NULL"
			link = 'selenium_restaurant_html/' + data.name[i] + '.html'
			soup = BeautifulSoup(open(link, encoding='utf-8'), 'lxml')
			title = soup.find('title').text
			if title == "Access to this page has been denied":
				print("Missed getting ", data.name[i])
				output = f"{data.link[i]}\n"
				m.write(output)
				continue
			else:
				name = data.name[i]
				link = data.link[i]
				restaurant_food_dict[restaurant_id] = []
				restaurant_list_script = soup.find('script', {"data-testid" : "restaurant-seo-schema"})
				if restaurant_list_script is not None:
				# print(restaurant_list_script)
					restaurant_dict = json.loads(restaurant_list_script.text)
					latitude = restaurant_dict['geo']['latitude']
					longitude = restaurant_dict['geo']['longitude']
				food_list = soup.find_all("li", class_="dish-card")
				if len(food_list) <= 0:
					print("Missed getting ", data.name[i])
					output = f"{data.link[i]}\n"
					m.write(output)
					continue
				for dish_card in food_list:
					product_name = dish_card.find('h3', class_="dish-name").text
					str_price = dish_card.find('span', class_="price p-price").text
					price = int(str_price.replace(',', '').replace('from ', '').replace('MMK ', ''))
					restaurant_food_dict[restaurant_id].append(food_id)
					insert_into_food = insert_into_food + f"""\n({food_id}, {restaurant_id}, "{product_name}", {price}),"""
					food_id += 1
				insert_into_restaurant = insert_into_restaurant + f"""\n({restaurant_id}, "{name}", {latitude}, {longitude}),"""
				restaurant_id += 1
		insert_into_restaurant = insert_into_restaurant[:-1] + ";" + "\n\n"
		insert_into_food = insert_into_food[:-1] + ";" + "\n\n"
		f.write(insert_into_restaurant)
		f.write(insert_into_food)
		last_restaurant_id = restaurant_id

		for i in range(len(name_data)):
			print("Getting data for name, ", i, " th loop")
			user_id = i + 1
			first_name = name_data.FirstName[i]
			surname = name_data.Surname[i]
			insert_into_user = insert_into_user + f"""\n({user_id}, "{first_name}", "{surname}"),"""
		insert_into_user = insert_into_user[:-1] + ";" + "\n\n"
		f.write(insert_into_user)
		last_user_id = user_id
	


		for i in range(50):
			print("Getting data for cart, ", i, " th loop")
			cart_id = i + 1
			user_id = rand.randint(1, last_user_id)
			restaurant_id = rand.randint(1, last_restaurant_id)
			cart_restaurant_dict[cart_id] = restaurant_id
			insert_into_cart = insert_into_cart + f"""\n({cart_id}, {user_id}, {restaurant_id}),"""
		insert_into_cart = insert_into_cart[:-1] + ";" + "\n\n"
		f.write(insert_into_cart)
		last_cart_id = cart_id

		for i in range(300):
			print("Getting data for order, ", i, " th loop with restaurant_id ", restaurant_id)
			order_id = i + 1
			cart_id = rand.randint(1, last_cart_id)
			restaurant_id = cart_restaurant_dict[cart_id]
			food_id = rand.randint(restaurant_food_dict[restaurant_id][0], restaurant_food_dict[restaurant_id][-1])
			rating = rand.randint(1, 10)
			insert_into_order = insert_into_order + f"""\n({order_id}, {food_id}, {cart_id}, {rating}),"""
		insert_into_order = insert_into_order[:-1] + ";" + "\n\n"
		f.write(insert_into_order)
			
				
				
