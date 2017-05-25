-- CART

DELETE FROM CART WHERE USER_ID = :id

DELETE FROM CART WHERE USER_ID = :id AND PRODUCT_ID = :p_id

UPDATE CART SET QUANTITY = :quan WHERE USER_ID = :id AND PRODUCT_ID = :p_id

SELECT p.NAME, c.QUANTITY, p.ID FROM PRODUCTS P
JOIN CART c on P.ID = c.PRODUCT_ID
WHERE c.USER_ID = :id

-- PRODUCTS

SELECT count(*) as "COUNT" from PRODUCTS

SELECT * FROM
        (SELECT * FROM
            (SELECT * FROM PRODUCTS where lower(NAME) like LOWER(:search) ORDER BY NAME ASC)
            WHERE ROWNUM <= :limit
        ORDER BY NAME DESC)
        WHERE ROWNUM <= :total

SELECT p.ID, p.name, p.description, p.stock, p.price, p.IMAGESOURCE, r.VALUE FROM PRODUCTS p
                                              JOIN PRODUCTRATINGS r ON p.ID = r.PRODUCT_ID
                                            WHERE p.ID = :product_id

-- USERS

SELECT * from users where username = :username and password = :password

select * from TABLE(crud_user.findUser('$username', '$password'))

begin crud_user.deleteUser(to_number($id)); end;

begin crud_user.updateUser( '$password' , '$email' , '$full_name' , '$adress' , '$phone' , to_date( '$date' , 'YYYY-MM-DD') , $id ); end;

insert into CART(ID, USER_ID, PRODUCT_ID, QUANTITY) VALUES (1, 12061, 7905, 1)