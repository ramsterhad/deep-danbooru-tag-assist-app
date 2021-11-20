# Deep Danbooru Tag Assist
Web-based assist application for an AI-based multi-label image classification system, based on [KichangKim´s DeepDanbooru](https://github.com/KichangKim/DeepDanbooru "KichangKim´s DeepDanbooru").

![screenshot](ddta_screenshot.jpg?raw=true "DDTA screenshot")
**[LIVE functional production server, login with danbooru username and API key](https://ddta.henta.hu/ "DDTA LIVE functional demonstration and production server").**  
*PLEASE BEWARE: SELECTED & SUBMITTED TAGS ARE ADDED TO DANBOORU FOR REAL*.  
An API key can be created and removed on your profile page: https://danbooru.donmai.us/profile. Please see https://danbooru.donmai.us/wiki_pages/help:api for further information.

## Data availability
We precomputed the images present in [Gwern's danbooru2020 SFW dataset](https://www.gwern.net/Danbooru2020 
"Gwen's danbooru 2020 SFW subset dataset") using the 4 RESNET models released by 
[KichangKim](https://github.com/KichangKim/DeepDanbooru "KichangKim´s DeepDanbooru") for deepdanbooru using a threshold 
of 0.100. In total, 3.227.713 images were classified using the 4 models, giving 12.910.852 tag prediction strings. The data 
is available under [releases](https://github.com/ramsterhad/deep-danbooru-tag-assist-app/releases/tag/danbooru2020 "DDTA danbooru 2020 SFW subset dataset tag prediction releases")

## Installation
Deepdanbooru tag assist requires PHP 8. Deepdanbooru and its dependencies can be installed inside a Miniconda environment
```shell
wget https://repo.anaconda.com/miniconda/Miniconda3-py39_4.9.2-Linux-x86_64.sh
bash https://repo.anaconda.com/miniconda/Miniconda3-py39_4.9.2-Linux-x86_64.sh
conda create -n ml
conda activate ml
conda install python=3.6
wget https://github.com/KichangKim/DeepDanbooru/archive/master.zip
unzip master.zip 
cd deepdanbooru/
pip install .[tensorflow]
```
Download the Deepdanbooru RESNET model:
```shell
wget https://github.com/KichangKim/DeepDanbooru/releases/download/v3-20200915-sgd-e30/deepdanbooru-v3-20200915-sgd-e30.zip
mkdir deepdanbooru-v3-20200915-sgd-e30
cd deepdanbooru-v3-20200915-sgd-e30/
unzip deepdanbooru-v3-20200915-sgd-e30.zip
```
Download the deepdanbooru tag assist [ZIP](https://github.com/ramsterhad/deep-danbooru-tag-assist-app/archive/main.zip) package from the 
[main repository](https://github.com/ramsterhad/deep-danbooru-tag-assist-app/tree/main) and unzip the files into the
target directory of your webserver (e.g. apache: /var/www/html/).
```shell
wget https://github.com/ramsterhad/deep-danbooru-tag-assist-app/archive/refs/tags/v1.0.0.zip
unzip v1.0.0.zip
```
Optional but advised: browse to the `db/` directory (e.g. /var/www/html/db) and download a precomputed database (suggested: v3):
```shell
wget https://github.com/ramsterhad/deep-danbooru-tag-assist-app/releases/download/danbooru2020/danbooru2020_deepdanbooru_v3-2020-09-15.7z
7z x danbooru2020_deepdanbooru_v3-2020-09-15.7z
```
Optional but advised: Configure your web server to serve `public/` as the webroot.

## Configuration
Deepdanbooru is called by [ml.sh](bin/ml.sh "ml.sh").  
Change `source` to the Miniconda directory (e.g. /home/username/miniconda3/etc/profile.d/conda.sh)  
Change `PROJECTPATH` to the RESNET model directory (e.g. /home/username/deepdanbooru-v3-20200915-sgd-e30/

To adapt the configuration of DDTA, adapt the `.env` file or create `.env.local` so an update doesn't overwrite your configuration. 

| Parameter                                  | default value                      |
|--------------------------------------------|------------------------------------|
| danbooru_api_url                           | https://danbooru.donmai.us/        |
| danbooru_default_request                   | limit=1&tags=order:random+rating:s |
| danbooru_user                              | empty                              |
| danbooru_pass                              | empty                              |
| machine_learning_platform_repository_debug | false                              |
| tags_min_score                             | 0.500                              |
| picture_storage                            | tmp                                |
| limit_for_suggested_tags                   | 15                                 |
| highlight_color_attributes                 | red,green,blue,pink,black,silver,purple,<br>grey,white,brown,orange,blonde,aqua,<br>dark_blue,dark_green,light_blue,<br>light_brown,light_green,light_purple,pink,<br>platinum_blonde,purple,translucent,yellow |
| tag_suggestion_exclude_list                | empty                              |
| debug                                      | false                              |

### danbooru_api_url
The default API domain for the Danbooru platforms API. At this moment, only danbooru.donmai.us and its derivative domains 
are supported. Support for other \*boorus using compatible APIs will follow in future releases. URL must end in a trailing 
slash. You can change the whole request URL also directly on the page in the input field on top of it. The input field 
overwrites the environment variable as long as the session cookie lives.
  
### danbooru_default_request
The default request URL for the Danbooru platforms API request. During the process this string is going to be appended 
to the config variable `danbooru_api_url`: `${danbooru_api_url}.'posts.json?'.${danbooru_default_request}`. For example:
```
danbooru_api_url=https://example.com/
danbooru_default_request=bar

becomes:

https://example.com/posts.json?bar
```
You can change the whole request URL also directly on the page in the input field on top of it. The input field overwrites 
the environment variable as long as the session cookie lives.

### danbooru_user & danbooru_pass
To be able to submit new tags to Danbooru, you need to be a registered member and provide your API credentials.
To create a session you need to provide credentials in the form of your username and API key. Alternatively, credentials 
can be placed into the `.env` config file when locally hosting ddta. The API key is different from your password and can 
be created at your profile page at Danbooru. Please see the [Authentication](https://danbooru.donmai.us/wiki_pages/help:api)
section at their API manual.

### machine_learning_platform_repository_debug
If set to true, the actual MLP will not be called, but a defined array of example tags will be returned. This is for 
testing only and should not be used in production.

### tags_min_score
Tags got a confidence score by the machine learning platform. From 1 to 0. The threshold 0.500 is well tested. 

### picture_storage
The image is downloaded from danbooru and temporarily locally stored in case it is to be forwarded to the machine 
learning platform (MLP). Needs write rights.

### limit_for_suggested_tags
Limits the checkboxes for suggested new tags. By default, a row contains 3 columns. So a number dividable by 3 is 
recommended. Using the numpad, the first 9 suggested tags can be toggled on/off. 

### highlight_color_attributes
Highlights tags which are already present at Danbooru, but were also suggested from Deep Danbooru, but with a different 
color.  
Example: `hair_yellow` <-> `hair_orange`

### tag_suggestion_exclude_list
A whitespace separated list to exclude tags to be suggested.  
Example: `tag_suggestion_exclude_list=tag1 tag2 tag3`

### debug
Activates a logging for Post Responses, e.g. the answer from the danbooru platform.  

## Examples of danbooru api calls
````txt

Members and anon cannot search for more than two tags at a time. Gold users can search for up to six tags, and Platinum and above users can search for up to twelve tags

Posts are categorized into safe for work (s), questionable (q) and explicit (e)
Safe for work:                        rating:s
Questionable / unrated:               rating:q
Explicit, not safe for work:          rating:e
The api returns posts up until limit=200 or smaller. The default order is ID, but can also be score, favcount, random, etc
Random order:                         order:random
Random safe image:                    https://danbooru.donmai.us/posts.json?limit=1&tags=order:random+rating:s
Random safe image with low tagcount:  https://danbooru.donmai.us/posts.json?limit=1&tags=order:random+rating:s+tagcount:%3C10

Tags are categorized into copyright tags (series name), artist tags, character tags (names), general tags (features), and metatags (features of the file)
Artist tag:                           arttags:<10
General tags:                         gentags:<10
Character tags:                       chartags:<10
Copyright tags:                       copytags:<10
Metatags:                             metatags:<10
Random safe image with <10 gentags:   https://danbooru.donmai.us/posts.json?limit=1&tags=order:random+rating:s+gentags:%3C10

File attributes such as hash, bytes and resolution:
Specific MD5 hash:                    https://danbooru.donmai.us/posts.json?tags=md5:460c5595dcf9b07d58f951d349202d98
Maximum 2 mb:                         filesize:..2M
Minimum 150kb:                        filesize:150kb..
Random safe image <10mb:              https://danbooru.donmai.us/posts.json?limit=1&tags=order:random+rating:s+filesize:..10M
Maximum 5 megapixel:                  mpixels:..5
Minimum 1 megapixel:                  mpixels:1..
Random safe image 10mpixel:           https://danbooru.donmai.us/posts.json?limit=1&tags=order:random+rating:s+mpixels:..10

````  
  
  

## Development

### Debugging

Configuration for PHPStorm
```text
Run/Debug Configurations::PHP Web Page

Name: localhost

Configuration
Server: Docker (see below)
Start URL: /
```

```text
Servers

Name: Docker
Host: localhost : Port 80
(yes) Use path mappings

/danbooru/data/www/ -> /usr/local/apache2/htdocs
```


### Testing

#### Install the environment
```shell
git clone https://github.com/ramsterhad/deep-danbooru-tag-assist-app/ ddta
cd ddta
composer install
```

#### PHPUnit

Execute all tests: `vendor/bin/phpunit -c tests/Unit/phpunit.xml`  

Execute one test: 
`vendor/bin/phpunit -c tests/Unit/phpunit.xml --filter testTransformJsonStringToObject`

#### PHPUnit Debugging

Configuration for PHPStorm
```text
Run/Debug Configurations::PHP Script

Name: phpunit

Configuration

File: vendor/phpunit/phpunit/phpunit
Arguments: ../../../tests .
```
