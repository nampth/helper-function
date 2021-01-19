# helper-function
Useful helper function for PHP

## 1) post_file
Using PHP CURL to submit file(s) to remote API with base64 data

* params @url: remote API path
* param @files: array Files with format 
    ```
       [ 
          [
            'file_data'=> '...' ,
            'name'=>'...'
          ]
          , ...  // item 2, 3, ... n
       ] 
   ```

## 2) remove_unicode_characters
Using php to remove unicode character

 * param @input: input string which you want to format
 * param @removeSpace: remove white space or not
 * param @$specialCharacter: remove special character or not