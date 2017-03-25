import MySQLdb

CLASSIFIER_DATA_FILE = "classifier_data.txt"


# Database connecting
conn = MySQLdb.connect(host= "127.0.0.1",
                  user="root",
                  #passwd="root",
                  db="classifier",
                  port=3306)
x = conn.cursor()


# Delete old database
try:
    x.execute("""DELETE FROM storage""")
    x.execute("""DELETE FROM classifier_spam""")
    conn.commit()
except:
    conn.rollback()

# Read CLASSIFIER_DATA_FILE and put the data into database
with open(CLASSIFIER_DATA_FILE) as myfile:
    data = myfile.readlines()

    terms_list = []  # create a new array

    for line in data[2:]:  ## read data after line 2 and acquire element data
        try:
            term = line.split()[0]  # at every line split term
            terms_list.append(term)  ## append to new arra
            
            print "--PROCESSING DATA %s---"%(line)
        except:
            print line
            raise
    terms_list = ','.join(terms_list)  # join by comma separation to terms_list
    
    ## reads the first two line sand stores in var 
    prob_spam = data[0].split()[1]  # reads the spam at element 0 assigns to spam var
    
    prob_non_spam = data[1].split()[1]   #reads the non spam elements and assigns to no non spam var
    terms_p = data[2:] ## same again

	
	#stores the total split of spam and non spam to another table
    try:
        print terms_list 
        #print "---PROCESSING DATA %s---"% (line,)
        x.execute("""INSERT INTO storage VALUES (%s,%s)""",("terms_list",terms_list))
        x.execute("""INSERT INTO storage VALUES (%s,%s)""",("prob_spam",prob_spam))
        x.execute("""INSERT INTO storage VALUES (%s,%s)""",("prob_non_spam",prob_non_spam))
        conn.commit()
    except:
        raise
        conn.rollback()


    for term_p_line in terms_p:
        items = term_p_line.split()
        term = items[0]
        p_spam = items[1]
        p_non_spam = items[2]
        #p_term = 1  # making the third last value as 1 

        try:
            x.execute("""INSERT INTO classifier_spam(term,p_spam,p_non_spam) VALUES (%s,%s,%s)""",(term,p_spam,p_non_spam))
            conn.commit()
        except:
            raise
            conn.rollback()

conn.close()

print "Upload to MYSQL database complete"
