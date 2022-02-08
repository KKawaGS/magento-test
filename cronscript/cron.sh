#!/bin/bash

MYSQLHOST='' #mysql server address
MYSQLUSER=''    #username
MYSQLPASSWD='' #passwordd
MYSQLDB=''   #database
FILEPATH="/var/lib/mysql-files/" #path for the output *.csv file

FILENAME="conf_product_export_$(date +"%F_%H%M%S").csv" #csv filename based run time

FILE=${FILEPATH}${FILENAME}

echo "Cron export starts: $(date)"

#mysql query: output is all configurable products with id, sku, name and price
mysql --user=${MYSQLUSER} --password=${MYSQLPASSWD} --host=${MYSQLHOST} ${MYSQLDB} <<EOF
SELECT  e.entity_id as 'product_id',
        e.sku,
        e.type_id,
        cpev1.value as 'name',
        cped.value as 'price'
FROM catalog_product_entity e
LEFT JOIN catalog_product_entity_varchar cpev1 on e.entity_id = cpev1.entity_id
and cpev1.store_id = 0
and cpev1.attribute_id = (
    select attribute_id from eav_attribute
        where attribute_code = 'name'
        and entity_type_id = (select entity_type_id from eav_entity_type where entity_type_code = 'catalog_product')
    )
LEFT JOIN catalog_product_entity_decimal cped ON e.entity_id = cped.entity_id
and cped.store_id = 0
and cped.attribute_id = (
    select attribute_id from eav_attribute
        where attribute_code = 'price'
        and  entity_type_id = (
                select entity_type_id from eav_entity_type where entity_type_code = 'catalog_product'
            )
        )
where type_id = 'configurable'
INTO OUTFILE '$FILE' FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n';
EOF

#add row with the column titles to the file
sed -i '1i product_id,SKU,type_id,name,price' "${FILE}"
echo "Cron export ends: $(date)"
