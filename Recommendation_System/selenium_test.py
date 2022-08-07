from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from webdriver_manager.chrome import ChromeDriverManager
 
options = Options()
options.add_argument('--headless')
options.add_argument('--no-sandbox')
options.add_argument('--disable-dev-shm-usage')
chromedriver_link = "/home/kan/.wdm/drivers/chromedriver/linux64/99.0.4844/chromedriver"
driver = webdriver.Chrome(chromedriver_link)
# driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)

with open("def.html", "w") as f:
	# driver.get("https://www.python.org/")
	# elem = driver.find_element(By.CLASS_NAME, "content-wrapper")
	driver.get("https://www.foodpanda.com.mm/restaurant/k2fo/conifer46")
	# elem = driver.find_elements_by_xpath("//*[@class='dish-category-section']")
	# elements = driver.find_elements(By.CLASS_NAME, "dish-card")
	# for elem in elements:
	# 	f.write(str(elem) + '\n')
	# 	print(elem)
	# driver.close()

	elem = driver.page_source
	f.write(elem)
	print(elem)

