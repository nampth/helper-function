# helper-function
Useful helper function for PHP

## 1) postFile
Using PHP CURL to submit file(s) to remote API with base64 data

- params @url: remote API path
- param @files: array File with format 
    ```[ 
          [
            'file_data'=> '...' ,
            'name'=>'...'
          ]
          , ...  // item 2, 3, ... n
       ] 
   ```
