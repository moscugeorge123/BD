--select index
EXPLAIN PLAN FOR
select * from TABLE(MEMBER_FUNCTIONS.BEST_SUPPORT_USER());

SELECT PLAN_TABLE_OUTPUT FROM TABLE(DBMS_XPLAN.DISPLAY());

DROP INDEX best_supp_index;
CREATE UNIQUE INDEX best_supp_index ON COMMENTS(USER_ID);

--function index
EXPLAIN PLAN FOR
SELECT * FROM
        (SELECT * FROM
            (SELECT * FROM PRODUCTS where lower(NAME) like LOWER('%Warcraft%') ORDER BY NAME ASC)
            WHERE ROWNUM <= 50
        ORDER BY NAME DESC)
        WHERE ROWNUM <= 50;

SELECT PLAN_TABLE_OUTPUT FROM TABLE(DBMS_XPLAN.DISPLAY());

DROP INDEX search_prod_index;
CREATE INDEX search_prod_index ON PRODUCTS(lower(NAME));

--something something index
EXPLAIN PLAN FOR
   SELECT SUM(CR.VALUE)
      FROM STORE_DB.COMMENTRATINGS CR
        JOIN STORE_DB.COMMENTS CM ON CR.COMMENT_ID = CM.ID
      WHERE CM.USER_ID = 500;

SELECT PLAN_TABLE_OUTPUT FROM TABLE(DBMS_XPLAN.DISPLAY());

DROP INDEX comm_rt_index;
CREATE INDEX comm_rt_index ON COMMENTRATINGS(ID,COMMENT_ID);