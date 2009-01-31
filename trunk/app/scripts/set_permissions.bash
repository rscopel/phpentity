#!/bin/bash
#
# Sets required permissions on directories for web write access.
#
# $LastChangedDate$
# $LastChangedRevision$
# $LastChangedBy$
#

WORKING_DIR=`dirname $0`/../domains/

DIRS2PROCESS="
  */sessions/
  */logs/
  */smarty/cache
  */smarty/compile
  */tmp
  "

cd "$WORKING_DIR"
chmod o+rwx $DIRS2PROCESS

exit 0
