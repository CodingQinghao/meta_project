import pymysql
import re
conn = pymysql.connect(
    host="127.0.0.1",
    user="",password="",
    database="",
    charset="utf8",cursorclass=pymysql.cursors.DictCursor)

    
def update(info):
    global conn
    cursor = conn.cursor()
    cursor.execute("UPDATE `word` SET `kr` = '"+info['kr']+"' WHERE `id` = '"+str(info['id'])+"'")
    conn.commit()

def insert(info):
    global conn
    cursor = conn.cursor()
    cursor.execute("INSERT INTO `metateam`.`word` (`kr`, `en`) VALUES ('"+str(info['kr'])+"', '"+str(info['en'])+"')")
    conn.commit()

def delete(info):
    global conn
    cursor = conn.cursor()
    cursor.execute("DELETE FROM `metateam`.`word` WHERE `id` = '"+str(info['id'])+"'")
    conn.commit()


def split_data():
    global conn
    cursor = conn.cursor()
    ids  = []
    cursor.execute("SELECT * FROM `word` where kr like '%7%'")
    result = cursor.fetchall()
    
    for info in result:
        kr_sentence_list = info['kr'].split('7')
        for kr_sentence in kr_sentence_list:
            new_sentence = {}
            new_sentence["kr"] = kr_sentence
            new_sentence["kr"] = new_sentence["kr"].strip()
            new_sentence["en"] = info["en"]
            new_sentence["id"] = info["id"]
            insert(new_sentence)
        delete(info)

#split_data()


def selete_delete_data():
    global conn
    cursor = conn.cursor()
    ids  = []
    cursor.execute("SELECT * FROM `word` where kr like '%?%'")
    result = cursor.fetchall()
    
    for info in result:
        delete(info)


def selete_replace_data():
    global conn
    cursor = conn.cursor()
    ids  = []
    cursor.execute("SELECT * FROM `word`")
    result = cursor.fetchall()
    
    for info in result:
        #info["kr"] = info["kr"].replace("-", "")
        info["kr"] = info["kr"].strip()
        info["en"] = info["en"].strip()
        update(info)


#selete_replace_data()

def test():
    global conn
    cursor = conn.cursor()
    ids  = []
    cursor.execute("SELECT * FROM `word`")
    result = cursor.fetchall()
    
    for info in result:
        sql = "SELECT count(*) as s_count FROM `word` where kr = '"+str(info["kr"])+"' and en = '"+str(info["en"])+"'"
        cursor.execute(sql)
        line = cursor.fetchall()
        if (line[0]["s_count"] > 1):
            delete(info)
        
        
test()