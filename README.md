# Deep Danbooru Tag Assist

## Installation

Download the [ZIP](https://github.com/ramsterhad/deep-danbooru-tag-assist-app/archive/main.zip) package from the 
[main repository](https://github.com/ramsterhad/deep-danbooru-tag-assist-app/tree/main) and unzip the files into the
target directory of your webserver (e.g. apache: /var/www/html/public).  
  
Make sure to place the database files into the directory `db/` in the root directory of this app.

## Configuration
To adapt the configuration, create the file `.env.local` next to `.env` and copy the configuration parameter you want 
to change to new file. 

### danbooru_api_url
##### default
https://danbooru.donmai.us/  
##### about
This is the default API domain for the Danbooru platforms API. Be aware to always have a trailing slash.  
You can change the whole request URL also directly on the page in the input field on top of it. The input field 
overwrites the environment variable as long as the session cookie lives.
  
  
### danbooru_default_request
##### default
````
limit=1&tags=order:random+rating:s  
````
##### about
This is the default request URL for the Danbooru platforms API request. During the process this string is going to be 
appended to the config variable `danbooru_api_url`: `${danbooru_api_url}.'posts.json?'.${danbooru_default_request}`. 
For example:
```
danbooru_api_url=https://example.com/
danbooru_default_request=bar

becomes:

https://example.com/posts.json?bar
```
You can change the whole request URL also directly on the page in the input field on top of it. The input field 
overwrites the environment variable as long as the session cookie lives.


### danbooru_user & danbooru_pass
##### default
````
empty
````
##### about
To be able to suggest new tags at Danbooru, you need to be logged in. To create a session you need to provide 
credentials either by the login form or by putting them into the config file.   
Please be aware that the API don't need or want your password from the Danbooru website. You must provide an API key, 
which you can create at your profile page at Danbooru. Please see the 
"[Authentication](https://danbooru.donmai.us/wiki_pages/help:api)" section at their API manual.

### machine_learning_platform_repository_debug
##### default
````
false
````
##### about
If set to true, the actual MLP will not be called, but a defined array of example tags will be returned. This is for
testing only and should not be used in production mode.

### tags_min_score
##### default
````
0.500
````
##### about
Tags got an rating by the machine learning platform. From 1 to 0. The threshold 0.500 is well tested. 

### picture_storage
##### default
````
tmp 
````
##### about
The MLP needs a picture to scan it. The app downloads the main picture of the current shown post and needs to store it
somewhere. Make sure the folder has fitting rights.


### limit_for_suggested_tags
##### default
````
15 
````
##### about
Limits the checkboxes for suggested new tags.  
By default a row contains 3 columns. So a number dividable by 3 is recommended.

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
  
  
## Testing

### Install the environment
```shell
git clone https://github.com/ramsterhad/deep-danbooru-tag-assist-app/ ddta
cd ddta
composer install
```

### Testing

Execute all tests: `vendor/bin/phpunit -c tests/Unit/phpunit.xml`  

Execute one test: 
`vendor/bin/phpunit -c tests/Unit/phpunit.xml --filter testTransformJsonStringToObject`