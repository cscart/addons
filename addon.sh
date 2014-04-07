#!/bin/bash

if (( $# < 3 ))               # Looking for exactly one parameter
then
    echo "Usage: ./addon.sh addon_name (work|commit|create|delete) path_to_working_copy [-f]"
    exit 1                    # Exit on a usage error
fi

case "$2" in
    work)
	svn up "$1"
	rm -fr "$3"/addons/"$1"
	rm -fr "$3"/skins/base/customer/addons/"$1"
	rm -fr "$3"/skins/base/customer/images/"$1"
	rm -fr "$3"/skins/base/admin/addons/"$1"
	rm -fr "$3"/skins/base/mail/addons/"$1"
	cp -fr "$1"/* "$3"

	;;
    commit)
	cp -fr "$3"/addons/"$1" "$1"/addons/
	cp -fr "$3"/skins/base/customer/addons/"$1" "$1"/skins/base/customer/addons/
	cp -fr "$3"/skins/base/admin/addons/"$1" "$1"/skins/base/admin/addons/
	cp -fr "$3"/skins/base/mail/addons/"$1" "$1"/skins/base/mail/addons/
	#cp -fr "$3"/skins/base/customer/images/"$1" "$1"/skins/base/customer/images
	
	;;
     create)
	if [ "$4" = '-f' ] # force
	then
		rm -rf "$3"/addons/"$1"
		rm -rf "$3"/skins/base/customer/addons/"$1"
		rm -rf "$3"/skins/base/admin/addons/"$1"
		rm -rf "$3"/skins/base/mail/addons/"$1"
		#rm -rf "$3"/skins/base/customer/images/"$1"
	fi
	ln -s `pwd`/"$1"/addons/"$1" "$3"/addons/
	ln -s `pwd`/"$1"/skins/base/customer/addons/"$1" "$3"/skins/base/customer/addons/
	ln -s `pwd`/"$1"/skins/base/admin/addons/"$1" "$3"/skins/base/admin/addons/
	ln -s `pwd`/"$1"/skins/base/mail/addons/"$1" "$3"/skins/base/mail/addons/
	#ln -s `pwd`"$1"/skins/base/customer/images/"$1" "$3"/skins/base/customer/images/
	
	;;
     delete)
	rm -rf "$3"/addons/"$1"
	rm -rf "$3"/skins/base/customer/addons/"$1"
	rm -rf "$3"/skins/base/admin/addons/"$1"
	rm -rf "$3"/skins/base/mail/addons/"$1"
	#rm -rf "$3"/skins/base/customer/images/"$1"
	
	;;
esac
