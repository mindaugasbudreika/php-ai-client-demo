# PHP AI CLient Demo

Example project demonstrating how to add a custom local AI provider (**Docker Model Runner, DMR**) to **[php-ai-client](https://github.com/WordPress/php-ai-client)** and use it within a WordPress plugin.


## Prerequisites

Before you begin, make sure you have the following installed:

1. **Composer** — [http://getcomposer.org/](http://getcomposer.org/)
2. **Docker** — [Install Docker](https://docs.docker.com/get-docker/)  
   Make sure your system meets the hardware requirements for Docker Model Runner (DMR):
   [https://docs.docker.com/ai/model-runner/#requirements](https://docs.docker.com/ai/model-runner/#requirements)

## Installation Instructions

1. **Clone the project**

   ```bash
   git clone https://github.com/mindaugasbudreika/php-ai-client-demo.git
   ```

2. **Install dependencies**

   ```bash
    cd hello-local-llm
    composer install
    ```

    This will download php-ai-client and other dependencies for the plugin.

3. **Start the environment**

   From the main project directory (containing docker-compose.yml):

   ```bash
   docker compose up -d
   ```

4. **Set up WordPress**

    Open [http://localhost:8080](http://localhost:8080) in your browser and follow the WordPress installation wizard.

5. **Activate the plugin**

    Go to Plugins → Hello Local LLM and activate it.

6. **Add the demo block**

   Edit any page, insert the Hello Local LLM block, and save.

7. **View the result**

   Open the page to see the random fact provided by your Local LLM! 🎉

## Related Resources

- [php-ai-client documentation](https://github.com/WordPress/php-ai-client)
- [Docker Model Runner documentation](https://docs.docker.com/ai/model-runner/)