# Introduction #

CoreDatabase contains basic database functionality.


# Requirements #

  * prepared statements (for security) for:
    * INSERT, UPDATE, SELECT, DELETE
    * allow for single or  multiple queries
    * return values to reflect type of query
  * relaxed SQL processing for non-prepared statements

# Proposed Methods #

## Public Methods ##

  * preparedInsert($sql, $arrData), preparedMultipleInsert($sql, $arrData)
  * preparedUpdate($sql, $arrData)
  * preparedDelete($sql, $arrData)
  * preparedSelect($sql, $arrData)

# Useful Links #

  * http://pear.php.net/manual/en/package.database.mdb2.intro-execute.php
  * http://balancedbraces.com/2008/06/10/prepared-statements-with-pears-mdb2/