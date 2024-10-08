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
													`userID` int(11) NOT NULL,
													`restaurantName` varchar(50) NOT NULL,
													`address` varchar(250),
													`latitude` double,
													`longitude` double,
													`image` varchar(200),
													`KPayPhoneNo` varchar(20)
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n"""
		f.write(create_restaurant)
		insert_into_restaurant = """INSERT INTO `restaurant` (`restaurantID`, `userID`, `restaurantName`, `address`, `latitude`, `longitude`, `image`, `KPayPhoneNo`) VALUES"""

		create_food = """CREATE TABLE `food` (
													`foodID` int(11) NOT NULL,
													`restaurantID` int(11) NOT NULL,
													`foodName` varchar(50) NOT NULL,
													`price` int(20),
													`stock` int(8),
													`image` varchar(200)
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n"""
		f.write(create_food)
		insert_into_food = """INSERT INTO `food` (`foodID`, `restaurantID`, `foodName`, `price`, `stock`, `image`) VALUES"""

		create_userRole = """CREATE TABLE `userRole` (
													`userRoleID` int(11) NOT NULL,
													`userRoleName` varchar(20) NOT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n"""
		f.write(create_userRole)
		insert_into_userRole = """INSERT INTO `userRole` (`userRoleID`, `userRoleName`) VALUES"""
		
		create_user = """CREATE TABLE `user` (
													`userID` int(11) NOT NULL,
													`userRoleID` int(11) NOT NULL,
													`firstName` varchar(20) NOT NULL,
													`lastName` varchar(20) NOT NULL,
													`email` varchar(40) NOT NULL,
													`password` varchar(30) NOT NULL,
													`address` varchar(250),
													`latitude` double,
													`longitude` double
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n"""
		f.write(create_user)
		insert_into_user = """INSERT INTO `user` (`userID`, `userRoleID`, `firstName`, `lastName`, `email`, `password`, `address`, `latitude`, `longitude`) VALUES"""

		create_cart = """CREATE TABLE `cart` (
													`cartID` int(11) NOT NULL,
													`userID` int(11) NOT NULL,
													`restaurantID` int(11) NOT NULL,
													`paymentTypeID` int(11) NOT NULL,
													`totalAmount` int(11),
													`address` varchar(250),
													`latitude` double,
													`longitude` double,
													`rating` int(3),
													`deliveryType` int(2),
													`cartStatus` int(2),
													`paymentStatus` int(2),
													`date` date NOT NULL,
  												`time` time NOT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n"""
		f.write(create_cart)
		insert_into_cart = """INSERT INTO `cart` 
													(`cartID`, 
													`userID`, 
													`restaurantID`,
													`paymentTypeID`,
													`totalAmount`,
													`address`,
													`latitude`,
													`longitude`,
													`rating`,
													`deliveryType`,
													`cartStatus`,
													`paymentStatus`,
													`date`,
													`time`) VALUES"""

		create_foodorder = """CREATE TABLE `foodorder` (
													`foodorderID` int(11) NOT NULL,
													`foodID` int(11) NOT NULL,
													`cartID` int(11) NOT NULL,
  												`quantity` int(8) NOT NULL,
													`rating` int(3) NOT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n"""
		f.write(create_foodorder)
		insert_into_foodorder = """INSERT INTO `foodorder` (`foodorderID`, `foodID`, `cartID`, `quantity`, `rating`) VALUES"""

		create_paymentType = """CREATE TABLE `paymentType` (
													`paymentTypeID` int(11) NOT NULL,
													`paymentType` varchar(20) NOT NULL
												) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n\n"""
		f.write(create_paymentType)
		insert_into_paymentType = """INSERT INTO `paymentType` (`paymentTypeID`, `paymentType`) VALUES"""
		

		food_id = 1
		restaurant_id = 1
		restaurant_food_dict = {}
		cart_restaurant_dict = {}
		cart_total_dict = {}
		food_price_dict = {}


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
					food_price_dict[food_id] = price
					restaurant_food_dict[restaurant_id].append(food_id)
					insert_into_food = insert_into_food + f"""\n({food_id}, {restaurant_id}, "{product_name}", {price}, {2}, ""),"""
					food_id += 1
				insert_into_restaurant = insert_into_restaurant + f"""\n({restaurant_id}, {restaurant_id}, "{name}", "", {latitude}, {longitude}, "", "09382956183"),"""
				restaurant_id += 1
		insert_into_restaurant = insert_into_restaurant[:-1] + ";" + "\n\n"
		insert_into_food = insert_into_food[:-1] + ";" + "\n\n"
		f.write(insert_into_restaurant)
		f.write(insert_into_food)
		last_restaurant_id = restaurant_id - 1


		userRole_name = ["Customer", "Admin", "Super Admin"]
		for i in range(len(userRole_name)):
			print("Getting data for userRole, ", i, " th loop")
			userRole_id = i + 1
			insert_into_userRole = insert_into_userRole + f"""\n({userRole_id}, "{userRole_name[i]}"),"""
		insert_into_userRole = insert_into_userRole[:-1] + ";" + "\n\n"
		f.write(insert_into_userRole)

		paymentType = ["Cash on delivery", "KBZPay"]
		for i in range(len(paymentType)):
			print("Getting data for paymentType, ", i, " th loop")
			paymentType_id = i + 1
			insert_into_paymentType = insert_into_paymentType + f"""\n({paymentType_id}, "{paymentType[i]}"),"""
		insert_into_paymentType = insert_into_paymentType[:-1] + ";" + "\n\n"
		f.write(insert_into_paymentType)

		for i in range(len(name_data)):
			print("Getting data for name, ", i, " th loop")
			user_id = i + 1
			first_name = name_data.FirstName[i]
			surname = name_data.Surname[i]
			email = first_name + surname + "@gmail.com"
			user_role_id = 2 if user_id <=245 else 1
			insert_into_user = insert_into_user + f"""\n({user_id}, {user_role_id}, "{first_name}", "{surname}", "{email}", "12345", "", {1}, {1}),"""
		insert_into_user = insert_into_user[:-1] + ";" + "\n\n"
		f.write(insert_into_user)
		last_user_id = user_id
	
		for i in range(100):
			print("Getting data for cart, ", i, " th loop")
			cart_id = i + 1
			restaurant_id = rand.randint(1, last_restaurant_id)
			cart_restaurant_dict[cart_id] = restaurant_id
			cart_total_dict[cart_id] = 0
		last_cart_id = cart_id

		# for i, cart_id in enumerate(cart_restaurant_dict.keys()):
		# 	print("Getting one data for foodorder, ", i, " th loop with restaurant_id ", restaurant_id)
		# 	foodorder_id = i + 1
		# 	restaurant_id = cart_restaurant_dict[cart_id]
		# 	food_id = rand.randint(restaurant_food_dict[restaurant_id][0], restaurant_food_dict[restaurant_id][-1])
		# 	cart_total_dict[cart_id] += food_price_dict[food_id]
		# 	rating = rand.randint(1, 5)
		# 	insert_into_foodorder = insert_into_foodorder + f"""\n({foodorder_id}, {food_id}, {cart_id}, {1}, {rating}),"""

		foodorder_id = 0
		for i, cart_id in enumerate(cart_restaurant_dict.keys()):
			restaurant_id = cart_restaurant_dict[cart_id]
			print("Getting data for foodorder, ", i, " th loop with cart_id ", cart_id, " and restaurant_id ", restaurant_id)
			rand_loop_count = rand.randint(1, 5)
			for j in range(rand_loop_count):
				if j >= len(restaurant_food_dict[restaurant_id]):
					continue
				foodorder_id = foodorder_id + 1
				food_id = restaurant_food_dict[restaurant_id][j]
				cart_total_dict[cart_id] += food_price_dict[food_id]
				rating = rand.randint(1, 10)
				insert_into_foodorder = insert_into_foodorder + f"""\n({foodorder_id}, {food_id}, {cart_id}, {1}, {rating}),"""
		insert_into_foodorder = insert_into_foodorder[:-1] + ";" + "\n\n"
		f.write(insert_into_foodorder)

		year = '2022'
		month = '10'
		day = 10
		for cart_id in cart_restaurant_dict:
			print("Fixing cart totalAmount of cart id ", cart_id)
			user_id = rand.randint(1, last_user_id)
			restaurant_id = cart_restaurant_dict[cart_id]
			totalAmount = cart_total_dict[cart_id]
			# '2022-02-23', '09:43:07'
			output_day = str(int(day + cart_id/10))
			date = year + '-' + month + '-' + output_day
			time = '09:43:07'
			insert_into_cart = insert_into_cart + f"""\n({cart_id}, {user_id}, {restaurant_id}, {1}, {totalAmount}, "", {1}, {1}, {3}, {0}, {0}, {0}, "{date}", "{time}"),"""
		insert_into_cart = insert_into_cart[:-1] + ";" + "\n\n"
		f.write(insert_into_cart)
				
