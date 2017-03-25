## WEKA format file generator will read spam datasets form Enron and write to a new file format .arff

import os
INPUT_FOLDER = ["enron1","enron2","enron3","enron4","enron5","enron6"]
#INPUT_FOLDER = ["enron1"]
SUB_FOLDER = ["ham","spam"]
FINAL_OUTPUT_FILE = "classifier_database_output.arff"  ### weka format file that will be created to use in WEKA 

HEADER = "@RELATION email \n\
\n\
@ATTRIBUTE subject  string\n\
@ATTRIBUTE content   string\n\
@ATTRIBUTE is_spam        {true, false}\n\
@DATA\n\
"


outputFile = open (FINAL_OUTPUT_FILE, "w")
outputFile.write(HEADER + "\n")
for folder in INPUT_FOLDER:
    print "PROCESSING FOLDER %s"% (folder,)

    for subfolder in SUB_FOLDER: #  identify folder
        path = folder + "/" + subfolder    #  path variable of folders stored in path var
        for fname in os.listdir(path):   # iteration to get the path names
            data = open(path + "/" + fname).read()    # store open paths to data variable
            lines = data.split("\n") #  split each line
            ## gets rid if the subject content
            subject = lines[0].replace("Subject:","").replace("subject:","").strip()  # strip certain texts
            subject = subject.replace('\\','')  ## replace all slashes 
            subject = subject.replace('"','\'') ## replace all inverted commas with slashes
            ##gets rid of content or specific content
            content = " \\n ".join(lines[1:])  # join after line 1
            content = content.replace('\\','')
            content = content.replace('"','\'')
            
            if subfolder=="ham":   # output after reading label at end of email as spam
                is_spam = "false"
            else:
                is_spam = "true"   # same  as above
            
            rowStr = '"%s","%s",%s\n' % (subject, content, is_spam, )
            outputFile.write(rowStr)
            #break
    
outputFile.close()

print "DONE. --Written to file--%s" %(FINAL_OUTPUT_FILE,)
