CREATE OR REPLACE DIRECTORY DUMP_DB_DATA AS 'C:\';
GRANT WRITE ON DIRECTORY DUMP_DB_DATA TO PUBLIC;

CREATE OR REPLACE PROCEDURE insertEaster(fileHandler UTL_FILE.FILE_TYPE) AUTHID CURRENT_USER AS
  v_pika VARCHAR2(4096) := '
--''quu..__
-- $$$b  `---.__
--  "$$b        `--.                          ___.---uuudP
--   `$$b           `.__.------.__     __.---''      $$$$"              .
--     "$b          -''            `-.-''            $$$"              .''|
--       ".                                       d$"             _.''  |
--         `.   /                              ..."             .''     |
--           `./                           ..::-''            _.''       |
--            /                         .:::-''            .-''         .''
--           :                          ::''\          _.''            |
--          .'' .-.             .-.           `.      .''               |
--          : /''$$|           .@"$\           `.   .''              _.-''
--         .''|$u$$|          |$$,$$|           |  <            _.-''
--         | `:$$:''          :$$$$$:           `.  `.       .-''
--         :                  `"--''             |    `-.     \
--        :##.       ==             .###.       `.      `.    `\
--        |##:                      :###:        |        >     >
--        |#''     `..''`..''         `###''        x:      /     /
--         \                                   xXX|     /    ./
--          \                                xXXX''|    /   ./
--          /`-.                                  `.  /   /
--         :    `-  ...........,                   | /  .''
--         |         ``:::::::''       .            |<    `.
--         |             ```          |           x| \ `.:``.
--         |                         .''    /''   xXX|  `:`M`M'':.
--         |    |                    ;    /:'' xXXX''|  -''MMMMM:''
--         `.  .''                   :    /:''       |-''MMMM.-''
--          |  |                   .''   /''        .''MMM.-''
--          `''`''                   :  ,''          |MMM<
--            |                     `''            |tbap\
--             \                                  :MM.-''
--              \                 |              .''
--               \.               `.            /
--                /     .:::::::.. :           /
--               |     .:::::::::::`.         /
--               |   .:::------------\       /
--              /   .''               >::''  /
--              `'',:                 :    .''
--                                   `:.:''
--   Pika Pika!
';
  BEGIN
    UTL_FILE.PUT(fileHandler, v_pika);
  END;

CREATE OR REPLACE PROCEDURE tableData(fileHandle UTL_FILE.FILE_TYPE, tableName VARCHAR2) AS
  v_cursor    NUMBER;
  v_query     VARCHAR2(1024);
  v_clobSub   VARCHAR2(1024);

  v_colVal    VARCHAR2(4096);
  v_clob      CLOB;
  v_blob      BLOB;
  v_colNum    NUMBER;
  v_descTable DBMS_SQL.DESC_TAB;
  v_ret       NUMBER;
  v_chr       VARCHAR(2);
  v_offset    INTEGER;
  BEGIN
    v_cursor := DBMS_SQL.OPEN_CURSOR;
    v_query := 'SELECT * FROM ' || tableName;

    DBMS_SQL.PARSE(v_cursor, v_query, DBMS_SQL.NATIVE);
    DBMS_SQL.DESCRIBE_COLUMNS(v_cursor, v_colNum, v_descTable);

    FOR i IN 1..v_colNum LOOP
      IF v_descTable(i).col_type = 112
      THEN
        DBMS_SQL.DEFINE_COLUMN(v_cursor, i, v_clob);
      ELSIF v_descTable(i).col_type = 113
        THEN
          DBMS_SQL.DEFINE_COLUMN(v_cursor, i, v_blob);
      ELSE
        DBMS_SQL.DEFINE_COLUMN(v_cursor, i, v_colVal, 4096);
      END IF;
    END LOOP;

    v_ret := DBMS_SQL.EXECUTE(v_cursor);
    WHILE (DBMS_SQL.FETCH_ROWS(v_cursor) > 0)
    LOOP
      UTL_FILE.PUT(fileHandle, 'INSERT INTO ' || tableName || ' VALUES(');
      FOR i IN 1..v_colNum LOOP

        IF i = v_colNum
        THEN
          v_chr := ');';
        ELSE v_chr := ',';
        END IF;

        IF v_descTable(i).col_type IN (1, 12, 180)
        THEN
          DBMS_SQL.COLUMN_VALUE(v_cursor, i, v_colVal);
          UTL_FILE.PUT(fileHandle, '''' || REPLACE(v_colVal, '''', '''''') || '''');
        ELSIF v_descTable(i).col_type = 112
          THEN
            v_offset := 1;
            DBMS_SQL.COLUMN_VALUE(v_cursor, i, v_clob);
            UTL_FILE.PUT(fileHandle, '''');

            LOOP
              EXIT WHEN v_offset > DBMS_LOB.GETLENGTH(v_clob);
              UTL_FILE.PUT(fileHandle, REPLACE(DBMS_LOB.SUBSTR(v_clob, 255, v_offset), '''', ''''''));
              v_offset := v_offset + 255;
            END LOOP;
            UTL_FILE.PUT(fileHandle, '''');

        ELSIF v_descTable(i).col_type = 113
          THEN
            DBMS_SQL.COLUMN_VALUE(v_cursor, i, v_blob);
            UTL_FILE.PUT(fileHandle, UTL_RAW.CAST_TO_RAW(UTL_RAW.CAST_TO_VARCHAR2(v_blob)) || v_chr);
        ELSE
          DBMS_SQL.COLUMN_VALUE(v_cursor, i, v_colVal);
          UTL_FILE.PUT(fileHandle, v_colVal);
        END IF;
        UTL_FILE.PUT(fileHandle, v_chr);
      END LOOP;
      UTL_FILE.PUT_LINE(fileHandle, chr(10));
    END LOOP;

    DBMS_SQL.CLOSE_CURSOR(v_cursor);
  END;

CREATE OR REPLACE PROCEDURE writeDataStructure(fileHandle   UTL_FILE.FILE_TYPE,
                                               v_structName VARCHAR2) AUTHID CURRENT_USER AS
  TYPE REF_CURSOR IS REF CURSOR;
  v_cursor REF_CURSOR;
  v_query  VARCHAR2(255);
  v_struct CLOB;
  l_offset NUMBER;
  BEGIN
    FOR rec IN (SELECT
                  object_name,
                  object_type
                FROM USER_OBJECTS
                WHERE object_type = v_structName) LOOP

      v_query := 'SELECT DBMS_METADATA.GET_DDL(''' || rec.object_type || ''',''' || rec.object_name ||
                 ''') FROM DUAL';


      OPEN v_cursor FOR v_query;
      LOOP
        FETCH v_cursor INTO v_struct;
        EXIT WHEN v_cursor%NOTFOUND;
        --write clob into file
        l_offset := 1;
        LOOP
          EXIT WHEN l_offset > DBMS_LOB.GETLENGTH(v_struct);
          UTL_FILE.PUT(fileHandle, DBMS_LOB.SUBSTR(v_struct, 255, l_offset));
          l_offset := l_offset + 255;
        END LOOP;

        UTL_FILE.PUT_LINE(fileHandle, ';');
      END LOOP;
    END LOOP;
  END;

CREATE OR REPLACE PROCEDURE writeTableData(fileHandle UTL_FILE.FILE_TYPE) AUTHID CURRENT_USER AS
  BEGIN
    FOR rec IN (SELECT object_name
                FROM USER_OBJECTS
                WHERE object_type = 'TABLE') LOOP

      tableData(fileHandle, rec.object_name);

      IF CEIL(DBMS_RANDOM.VALUE(0, 10)) = 7
      THEN
        INSERTEASTER(fileHandle);
      END IF;
    END LOOP;
  END;

CREATE OR REPLACE PROCEDURE writeTableStructure(fileHandle UTL_FILE.FILE_TYPE, v_name VARCHAR2) AUTHID CURRENT_USER AS
  TYPE REF_CURSOR IS REF CURSOR;
  v_cursor         REF_CURSOR;
  v_tabel          VARCHAR2(4096) DEFAULT '';
  v_first_seg      VARCHAR2(4096);
  v_query          VARCHAR2(1024);
  l_offset         INTEGER;
  v_struct         CLOB;
  v_fk_pos         INTEGER;
  v_constr_pos     INTEGER;
  v_prev           INTEGER;
  v_end_constr_pos INTEGER;
  v_start          INTEGER;
  v_end            INTEGER;
  BEGIN
    FOR rec IN (SELECT object_name
                FROM USER_OBJECTS
                WHERE object_name = v_name) LOOP

      v_query := 'SELECT DBMS_METADATA.GET_DDL(''TABLE'',''' || rec.object_name ||
                 ''') FROM DUAL';


      v_start := 0;
      OPEN v_cursor FOR v_query;
      --get data into tabel

      FETCH v_cursor INTO v_struct;
      EXIT WHEN v_cursor%NOTFOUND;
      --write clob into file
      l_offset := 1;
      LOOP
        EXIT WHEN l_offset > DBMS_LOB.GETLENGTH(v_struct);
        v_tabel := v_tabel || DBMS_LOB.SUBSTR(v_struct, 255, l_offset);
        l_offset := l_offset + 255;
      END LOOP;

      --remove FK constraints
      v_fk_pos := 0;
      LOOP
        v_constr_pos := 0;
        v_end_constr_pos := 0;
        v_fk_pos := INSTR(v_tabel, 'FOREIGN KEY', v_fk_pos + 1);
        EXIT WHEN v_fk_pos = 0;

        LOOP
          v_prev := v_constr_pos;
          v_constr_pos := INSTR(v_tabel, 'CONSTRAINT', v_constr_pos + 1);
          EXIT WHEN v_constr_pos = 0 OR v_constr_pos >= v_fk_pos;
        END LOOP;

        v_constr_pos := v_prev;

        IF v_constr_pos = 0 OR v_constr_pos >= v_fk_pos
        THEN
          CONTINUE;
        END IF;

        LOOP
          v_end_constr_pos := INSTR(v_tabel, 'CASCADE ENABLE', v_end_constr_pos + 1);
          EXIT WHEN v_end_constr_pos = 0 OR v_end_constr_pos > v_fk_pos;
        END LOOP;

        IF v_end_constr_pos = 0 OR v_end_constr_pos <= v_fk_pos
        THEN
          CONTINUE;
        END IF;

        IF v_start = 0
        THEN
          v_start := v_constr_pos;
        END IF;

        v_end := v_end_constr_pos;


      END LOOP;

      IF v_start > 0
      THEN

        v_first_seg := SUBSTR(v_tabel, 1, v_start - 6);
        UTL_FILE.PUT(fileHandle, v_first_seg);

        v_tabel := SUBSTR(v_tabel, v_end + 15);
        UTL_FILE.PUT(fileHandle, v_tabel);

      ELSE
        UTL_FILE.PUT(fileHandle, v_tabel);
      END IF;

    END LOOP;
  END;

CREATE OR REPLACE PROCEDURE writeConstraintsTable(fileHandle UTL_FILE.FILE_TYPE, v_name VARCHAR2) AUTHID CURRENT_USER AS
  TYPE REF_CURSOR IS REF CURSOR;
  v_cursor         REF_CURSOR;
  v_tabel          VARCHAR2(4096) DEFAULT '';
  v_query          VARCHAR2(1024);
  l_offset         INTEGER;
  v_struct         CLOB;
  v_fk_pos         INTEGER;
  v_constr_pos     INTEGER;
  v_prev           INTEGER;
  v_end_constr_pos INTEGER;
  BEGIN
    FOR rec IN (SELECT object_name
                FROM USER_OBJECTS
                WHERE object_name = v_name) LOOP

      v_query := 'SELECT DBMS_METADATA.GET_DDL(''TABLE'',''' || rec.object_name ||
                 ''') FROM DUAL';

      OPEN v_cursor FOR v_query;
      --get data into tabel

      FETCH v_cursor INTO v_struct;
      EXIT WHEN v_cursor%NOTFOUND;
      --write clob into file
      l_offset := 1;
      LOOP
        EXIT WHEN l_offset > DBMS_LOB.GETLENGTH(v_struct);
        v_tabel := v_tabel || DBMS_LOB.SUBSTR(v_struct, 255, l_offset);
        l_offset := l_offset + 255;
      END LOOP;

      --remove FK constraints
      v_fk_pos := 0;
      LOOP
        v_constr_pos := 0;
        v_end_constr_pos := 0;
        v_fk_pos := INSTR(v_tabel, 'FOREIGN KEY', v_fk_pos + 1);
        EXIT WHEN v_fk_pos = 0;

        LOOP
          v_prev := v_constr_pos;
          v_constr_pos := INSTR(v_tabel, 'CONSTRAINT', v_constr_pos + 1);
          EXIT WHEN v_constr_pos = 0 OR v_constr_pos >= v_fk_pos;
        END LOOP;

        v_constr_pos := v_prev;

        IF v_constr_pos = 0 OR v_constr_pos >= v_fk_pos
        THEN
          CONTINUE;
        END IF;

        LOOP
          v_end_constr_pos := INSTR(v_tabel, 'CASCADE ENABLE', v_end_constr_pos + 1);
          EXIT WHEN v_end_constr_pos = 0 OR v_end_constr_pos > v_fk_pos;
        END LOOP;

        IF v_end_constr_pos = 0 OR v_end_constr_pos <= v_fk_pos
        THEN
          CONTINUE;
        END IF;

        UTL_FILE.PUT_LINE(fileHandle, 'ALTER TABLE ' || v_name || ' ADD ' ||
                                      SUBSTR(v_tabel, v_constr_pos, v_end_constr_pos - v_constr_pos + 14) || ';');

      END LOOP;
    END LOOP;
  END;

CREATE OR REPLACE PROCEDURE EXPORT_DB AS
  fileHandle UTL_FILE.FILE_TYPE;
BEGIN
  fileHandle := UTL_FILE.FOPEN('DUMP_DB_DATA', 'database.sql', 'w', 32767);

  --remove FK constraints from file
  FOR rec IN (SELECT object_name
              FROM user_objects
              WHERE object_type = 'TABLE') LOOP
    writeTableStructure(fileHandle, rec.object_name);
    UTL_FILE.PUT_LINE(fileHandle, ';');
  END LOOP;

  --write other stuff
  writeDataStructure(fileHandle, 'SEQUENCE');
  writeDataStructure(fileHandle, 'TYPE');
  writeDataStructure(fileHandle, 'PACKAGE');
  writeDataStructure(fileHandle, 'PACKAGE_BODY');
  writeDataStructure(fileHandle, 'PROCEDURE');
  writeDataStructure(fileHandle, 'FUNCTION');
  writeDataStructure(fileHandle, 'VIEW');

  writeTableData(fileHandle);

  --add constraints
  FOR rec IN (SELECT object_name
              FROM user_objects
              WHERE object_type = 'TABLE') LOOP
    writeConstraintsTable(fileHandle, rec.object_name);
    UTL_FILE.PUT_LINE(fileHandle, '');
  END LOOP;

  writeDataStructure(fileHandle, 'TRIGGER');

  UTL_FILE.FCLOSE(fileHandle);
END;

SELECT
  line,
  position,
  text
FROM user_errors
WHERE type = 'TRIGGER'
      AND name = UPPER('comments_del_trig')
ORDER BY sequence;

BEGIN
  EXPORT_DB();
END;