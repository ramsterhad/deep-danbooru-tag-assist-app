#!/bin/bash
# This BASH script returns recognized visual features 
# (tags) of the input image $1. Each tag has a minimal 
# confidence $2
# Only JPEG and PNG are supported
# Example: bash ml.sh "/link/to/image.jpg" "0.500"

# Activate the conda environment "ml", which 
# contains deepdanbooru and its dependences
source ~/miniconda3/etc/profile.d/conda.sh
conda activate ml

# International python installs result in
# weird errors. To standardize the environment,
# set the lang to "C.UTF-8" 
export LC_ALL=C.UTF-8
export LANG=C.UTF-8 

# Define the path to the tensorflow model
PROJECTPATH="/mnt/f/deepdanbooru/deepdanbooru-v3-20200915-sgd-e30.zip/"

# Publicly available models:
# deepdanbooru-v1-20191108-sgd-e30.zip
# deepdanbooru-v3-20200101-sgd-e30.zip
# deepdanbooru-v3-20200915-sgd-e30.zip
# deepdanbooru-v4-20200814-sgd-e30.zip

# Call deepdanbooru to evaluate image $1 and tag threshold $2
deepdanbooru evaluate "$1" --project-path "$PROJECTPATH" --threshold "$2"
