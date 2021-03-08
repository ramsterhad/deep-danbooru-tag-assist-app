#!/bin/bash
# This BASH script returns recognized visual features (tags) of the input image $1. Each tag has a minimal confidence $2
# Example: bash ml.sh "/link/to/image.jpg" "0.500"

# Activate the conda environment "ml", which contains deepdanbooru and its dependences
source ~/miniconda3/etc/profile.d/conda.sh
conda activate ml
# International python get rekt
export LC_ALL=C.UTF-8
export LANG=C.UTF-8 

# Define the path to the tensorflow model
PROJECTPATH="/mnt/f/deepdanbooru/deepdanbooru-v3-20200915-sgd-e30.zip/"

# Call deepdanbooru to evaluate imafe $1 with tag threshold $2
deepdanbooru evaluate "$1" --project-path "$PROJECTPATH" --threshold "$2"
