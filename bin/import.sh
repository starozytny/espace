cd original || return 2
for file in *
  do
    firstcol=$(csvcut -d ";" -c 1 $file | head -n 1)
    csvsql -i mysql $file
    filename="windev_${file%.*}"
    csvsql --db mysql+mysqlconnector://root:@localhost:3306/cite_windev --overwrite --tables $filename --insert $file --query "ALTER TABLE ${filename} ADD PRIMARY KEY (${firstcol}); ALTER TABLE ${filename} CHANGE ${firstcol} id INT AUTO_INCREMENT NOT NULL;  ALTER TABLE ${filename} DROP BASE;"
    mv $file ../traiter/$file
done
