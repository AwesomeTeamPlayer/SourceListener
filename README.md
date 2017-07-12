# My project's README


## How to build server?
```bash
docker build -t awesometeamplayer/sourcelistener .
```

## How to run server?
```bash
docker run -it -p="80:80" -e REDIS_HOST=localhost -e REDIS_PORT=1234 -e PAGINATION_LIMIT=200 awesometeamplayer/sourcelistener
```

## Variables:

* REDIS_HOST
* REDIS_PORT
* PAGINATION_LIMIT

## How to run unit and integration tests?
```bash
docker run awesometeamplayer:sourcelistener /app/runTests.sh
```

## How to run docker tests?
```bash
cd ./docker_tests && ./runDockerTests.sh
```
Using this command you run docker env with Nginx and Redis. On the end of 
the command result you will see how many tests passed:
```bash
...........
test_1             | 
test_1             | Time: 363 ms, Memory: 6.00MB
test_1             | 
test_1             | OK (11 tests, 18 assertions)
```
Of course there always should be "OK" :)

