import mysql.connector

ref = mysql.connector.connect(
	host = "localhost",
	user = "sleep",
	password = "password",
	port = "8889",
	database = "myDB"
)

cursor = ref.cursor()

cursor.execute("SELECT slp_eff FROM `TABLE 1` WHERE age < 22 AND age > 17")

total = 0
i = 0
for x in cursor:
	total += float(x[0])
	i += 1

print(total/i)