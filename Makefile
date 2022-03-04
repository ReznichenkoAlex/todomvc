help:
	@echo 'make build - команда для сборки приложения'
	@echo 'make enterApp - команда для входа в контейнер приложения (может не работать, проверьте имя контейнера)'
	@echo 'changeSrcOwner	Команда для изменения владельца папки src на текущего пользователя'

changeSrcOwner:
	@sudo chown -R $$USER:$$USER src
enterApp:
	@docker exec -it docker_app_1 /bin/sh
build:
	@docker-compose -f ./docker/docker-compose.yaml up -d