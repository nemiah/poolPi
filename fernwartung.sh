# !/bin/bash

ssh -o StrictHostKeyChecking=no -R\*:2222:localhost:22 -R8888:localhost:80 -N nemiah@open3a.de
