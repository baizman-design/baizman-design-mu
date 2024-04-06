#!/usr/bin/env sh

# install alias in mu-plugins folder.

plugin_dir="baizmandesign/bzmndsgn-mu"
plugin="bzmndsgn-mu.php"
vendor_dir="$(composer config vendor-dir)"
wp_content_dir="wp-content"
mu_dir="${wp_content_dir}/mu-plugins"
MKDIR=$(which mkdir)
LN=$(which ln)

echo "Attempting to install alias..."

# check for wordpress instance
if [ -f "wp-config.php" ]
then
    # does mu-plugins exist? if not, make it.
    if [ ! -d "${mu_dir}" ]
    then
        ${MKDIR} ${mu_dir}
    fi
    # create link
    ${LN} -s ../../${vendor_dir}/${plugin_dir}/${plugin} ${mu_dir}/${plugin}
fi
