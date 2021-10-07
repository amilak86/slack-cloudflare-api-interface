# PHP Slack Notifier

This is a demo project demonstrating how to perform cloudflare operations through your slack workspace using custom slash commands.

## Requirements
- Heroku Free Plan with Heroku CLI installed on your computer
- LAMP or WAMP setup with Composer CLI
- A Slack Account
- A Cloudflare Account with one or more domains

## How to Run

- Clone this repository to your local computer by running `git clone git@github.com:amilak86/slack-cloudflare-api-interface.git`

- Switch to the cloned directory **(slack-cloudflare-api-interface)** and run `composer install` to install required dependencies

- Create a new PHP script named **config.php** and copy the contents of **config.sample.php** to the newly created config.php script

- Run `heroku create slack-cloudflare-api-interface` to create a new Heroku app

- Deploy your heroku app by running `git push heroku main`. Wait till the completion of the build process. Once it has been completed you should see a message similar to **https://your-app.herokuapp.com/ deployed to Heroku**. Copy that URL as we gonna need it to configure our slack slash commands.

- Sign-up with [Slack](https://slack.com) and Log into your account. Follow [https://api.slack.com/interactivity/slash-commands](https://api.slack.com/interactivity/slash-commands) to learn how to create a new slash command in slack. Once you understand the process, create and save two slash commands as described below:
	
	- Command 1:

		- Command: /clear-cache
		- Request URL: https://your-app.herokuapp.com/?a=clear-cache
		- Short Description: Clears the cloudflare cache of the given sitename
		- Usage Hint: [sitename]

	- Command 2:

		- Command: /dev-mode
		- Request URL: https://your-app.herokuapp.com/?a=dev-mode
		- Short Description: Switch the given sitename on cloudflare to the development mode 
		- Usage Hint: [sitename]

- Once the commands have been created, you can install your new slack app in your slack workspace. The installation option should be available under the 'Settings' section of your App's admin dashboard. If the app is installed properly, you should see it getting listed under the Apps section in your Slack workspace. You should be able to see the newly added commands by typing /clear-cache and /dev-mode into the slack input field under any channel. 

- To finish up with the slack setup, go to the 'Basic Information' section, scroll down until you see a field titled 'Signing Secret' under 'App Credentials' section. Click 'Show' to reveal the signing secret and copy and paste it into somewhere you can access later. 

- In your terminal, navigate back to your heroku app directory and run below command to add your **Slack Signing Secret** as a heroku environment variable:

	`heroku config:set SLACK_SIGNING_SECRET=your_signing_secret`

- Next we need to grab our cloudflare api access token. Log into your cloudflare account, go to your profile and navigate to the 'API Tokens' tab. There you'll have an option to create an API token. The process should self explanatory but if you need help please refer to [https://developers.cloudflare.com/api/tokens/create](https://developers.cloudflare.com/api/tokens/create) for detailed instructions. The important thing to remember here is that you must enable below permissions for our app to work as intended:

	- cache_purge:edit
	- zone_settings:edit

- Once you've finished creating the API Token, copy and paste it as the value of 'api_token' field replacing 'CLOUDFLARE_API_TOKEN' placeholder string in your config.php.

- Next add your cloudflare domains under the 'domains' array in your app's config.php. You should replace dummy entries such as abc.com and xyz.com. Those are there just as placeholder domains.

- If you've completed all the above steps, finally you should be ready to test out your slack to cloudflare integration by executing `/clear-cache yourcloudflaresite.com` or `/dev-mode yourcloudflaresite.com` in your slack workspace just as you would do with similar other slack commands. 

## License

[MIT](./LICENSE)

## Author

[Amila Kalansooriya](https://www.linkedin.com/in/amilakalansooriya/)