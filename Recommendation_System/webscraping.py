from os import closerange
from bs4 import BeautifulSoup
from pkg_resources import resource_listdir
import requests

i = 1
with open("bkup_restaurants" + ".csv", "w") as f:
	f.write("name,link\n")
	headers = {'User-Agent': 'window.navigator.userAgent'}
	html_text = requests.get('https://www.foodpanda.com.mm/en/city/yangon', headers=headers).text
	soup = BeautifulSoup(html_text, 'lxml')
	restaurant_list_ul = soup.find('ul', class_ = 'vendor-list')
	restaurant_list_a_s = restaurant_list_ul.find_all('a', href=True)
	print(str(len(restaurant_list_a_s)) + " restaurants found")
	for restaurant_list in restaurant_list_a_s:
		# print("Loop no ", i)
		name = restaurant_list.find('span', class_='name fn')
		mod_name = name.text.replace(',', '.').replace('/', '.')
		# print("Name is ", name)
		link = restaurant_list['href']
		output = f"{mod_name},{link}\n"
		f.write(output)
		i += 1
