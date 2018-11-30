# Storage Services

In many solutions being built, we will have to store files. These files can be an image, video, pdf, etc. The solutions here will address how to store those files.

## File Storage Service

Like the name sounds, the File Storage Service stores files on a local file system. This is a basic solution with many drawbacks because:

1. Local systems have a limit on how much space that they have
2. It puts the responsibility of security of storing the files on you
3. When files are read from the file system, it can cause latency.

## Cloud Storage Service

The optimal way of storing files is in one external file system. Examples of these are in s3, Firebase File system, Fastly, etc. For optimization, the files should be served via a CDN. While storing files in a provider has a cost, it comes with the benefit of:

1. Not having to worry about disk space usage on your servers
2. Not having to worry about securely storing the files (if you set your service providers preferences correctly)
3. Taking the I/O or disk operations off your servers

Our Cloud Storage Service, in this case, is specifically designed to work with s3. One of the areas to note I show the upload changes depends on file size:

```php
<?php
//If file size is smaller, then load into memory and upload to s3
if(file_exists($body) && filesize($body) < 2000000000) {
                
                $result = $s3->putObject(array(
                    'Bucket'       => $this -> _bucket,
                    'Key'          => $object,
                    'SourceFile'   => $body,
                    'ContentType'  => $content_type,
                    'ACL'          => $this ->_acl,
                    'curl.options' => array(
                           CURLOPT_TIMEOUT => 15000,
                        )
                ));
            
                return $result['ObjectURL'];
            
            } else {
                //For large files, break into chunks and upload
                $uploader = new MultipartUploader($s3, $body, [
                        'bucket' => $bucket,
                        'key'    => $object,
                        'acl'    => $this ->_acl,
                        'concurrency' => 2,
                        'part_size' => (50 * 1024 * 1024),
                        'before_initiate' => function(\Aws\Command $command) use ($content_type) {
                            $command['ContentType'] = $content_type;
                        }
                ]);
                
                    
                $result = $uploader->upload();
    
                return (isset($result['Location'])) ? $result['Location'] : false;
            }
?>
```

When uploading a large file, loading it into memory can be problematic for your server. For example, if your server has 1GB of RAM and you load a 1 GB video file into RAM to upload - you are out of memory! Instead, we want to focus on steaming those files byte by bye to our local storage.

#### Important Note

In developing, there will be scenarios of dealing with large amounts of data, ranging from 16GB video files to JSON files that are 100s of megabytes. Developers of to be conscious of developing solution that read through these files byte by byte.