#!/bin/bash
#
# Sets required permissions on directories for web write access.
#
# $LastChangedDate$
# $LastChangedRevision$
# $LastChangedBy$
#

if [ ! "$1" ]; then
  echo "ERROR: require domain"
  exit 1
fi

CUR_DIR=`dirname $0`
WORKING_DIR=`dirname $0`/../domains/

echo $CUR_DIR
cp -a "$WORKING_DIR""_template" "$WORKING_DIR""$1"
$CUR_DIR/set_permissions.bash
cd "$WORKING_DIR"
ls -la  
find "$1" -type d -name ".svn" -exec rm -rf {} \; 2>/dev/null

exit 0