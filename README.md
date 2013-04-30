
# Install

Install using composer

add the following to your config.yml to enable modified and created timestamps

stof_doctrine_extensions:
  orm:
    vmail:
      timestampable: true

#Optional overrides for vmail
lasso_vmail:
    root_mail_dir: /vmstore/vmail
    default_quota: 2147483648
