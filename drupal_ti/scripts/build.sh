#!/bin/bash
# Only continue if we are on the "develop" branch
echo $TRAVIS_COMMIT
if [[ $TRAVIS_BRANCH == *"develop"* ]]
then
  # Kick off slack /build command
  curl -i \
  -X POST --data-urlencode "text=rnd17 $TRAVIS_BRANCH"  "http://redirect:d0ntl00k@crapi.sys.comicrelief.com/craft/Slack-Builder/buildWithParameters?token=m6gcu9bbP7mqQqJy&tuser_name=Travis"
  echo "Building CRAFT artifact..."
fi
