# My project's README


## How to run unit and integration tests?
```
docker run awesometeamplayer:sourcelistener /app/runTests.sh
```

## How to run docker tests?
```
cd ./docker_tests && ./runDockerTests.sh
```
Using this command you run docker env with Nginx and Redis. On the end of 
the command result you will see how many tests passed:
```
...........
test_1             | 
test_1             | Time: 363 ms, Memory: 6.00MB
test_1             | 
test_1             | OK (11 tests, 18 assertions)
```
Of course there always should be "OK" :)

