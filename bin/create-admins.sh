#!/usr/bin/env bash

bin/console survos:user:create super@survos.com tt --roles ROLE_SUPER_ADMIN
bin/console survos:user:create tac@survos.com tt  --roles ROLE_ADMIN
bin/console survos:user:create 	florent.morselli@spomky-labs.com pwa  --roles ROLE_ADMIN
bin/console survos:user:create 	admin@survos.com admin --roles ROLE_ADMIN
bin/console survos:user:create  tacman@gmail.com tt --roles ROLE_ALLOWED_TO_SWITCH --roles ROLE_ADMIN
