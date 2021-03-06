$(function ($) {
	'use strict';

	Handlebars.registerHelper('eq', function (a, b, options) {
		return a === b ? options.fn(this) : options.inverse(this);
	});

	const App = {
		run: async function () {
			this.todoItems = await this.getTasks();
			this.todoTemplate = Handlebars.compile($('#todo-template').html());
			this.footerTemplate = Handlebars.compile($('#footer-template').html());
			this.filter = 'all';
			this.render();
			this.initEvents();
		},

		render: function () {
			let todos = this.getFilteredTodos();

			$('.todo-list').html(this.todoTemplate(todos));
			$('.main').toggle(todos.length > 0);
			$('.toggle-all').prop('checked', this.getActiveTasks().length === 0);

			const todoCount = this.todoItems.length;
			const activeTodoCount = this.getActiveTasks().length;

			$('.footer').toggle(this.todoItems.length > 0).html(this.footerTemplate({
				activeTodoCount: activeTodoCount,
				completedTodos: todoCount - activeTodoCount,
				filter: this.filter
			}));
			localStorage.setItem('data', JSON.stringify(this.todoItems));
			$('.new-todo').focus();
		},

		initEvents: function () {
			$('.new-todo').on('keyup', this.createTask.bind(this));

			$('.todo-list')
				.on('click', '.toggle', this.toggleTask.bind(this))
				.on('click', '.destroy', this.deleteTask.bind(this))
				.on('dblclick', 'label', this.switchEditingMode.bind(this))
				.on('keyup', '.edit', this.emitFocusout.bind(this))
				.on('focusout', '.edit', this.updateTask.bind(this));

			$('.toggle-all').on('click', this.toggleAll.bind(this));
			$('.clear-completed').on('click', this.deleteCompletedTasks.bind(this));
			$('.filters').on('click', 'li', this.filterTasks.bind(this))
		},

		getFilteredTodos: function () {
			if (this.filter === 'active') {
				return this.getActiveTasks();
			}
			if (this.filter === 'completed') {
				return this.getCompletedTasks();
			}

			return this.todoItems;
		},

		getCompletedTasks: function () {
			return this.todoItems.filter(todo => {
				return todo.isCompleted;
			});
		},

		getActiveTasks: function () {
			return this.todoItems.filter(todo => {
				return !todo.isCompleted;
			});
		},

		getTasks: async function () {
			let data = JSON.parse(localStorage.getItem('data'));
			if (!data || data.length === 0) {
				const res = await $.getJSON('/api/get');
				data = res.data;
				localStorage.setItem('data', JSON.stringify(data));
			}
			return data;
		},

		createTask: function (e) {
			let $input = $(e.target)
			let value = $input.val().trim();
			if (e.which === 13 && value) {
				const task = {
					uuid: this.uuidv4(),
					title: value,
					isCompleted: false
				}
				this.todoItems.push(task);
				$input.val('');
				this.render();
				this.sendAjaxJson('api/post', task, 'POST');
			}
		},

		updateTask: function (e) {
			let el = e.target;
			let $el = $(el);
			let val = $el.val().trim();

			if ($el.data('abort')) {
				$el.data('abort', false);
			} else if (!val) {
				this.deleteTask(e);
				return;
			} else {
				const index = this.getIndex(e)
				this.todoItems[index].title = val;
				this.sendAjaxJson('api/patch', this.todoItems[index], 'PATCH');
			}

			this.render();
		},

		deleteTask: function (e) {
			const index = this.getIndex(e);
			this.sendAjaxJson('api/delete', {uuid: this.todoItems[index].uuid}, 'DELETE');
			this.todoItems.splice(index, 1);
			this.render();
		},

		toggleTask: function (e) {
			const index = this.getIndex(e);
			this.todoItems[index].isCompleted = !this.todoItems[index].isCompleted;
			this.sendAjaxJson('api/patch', this.todoItems[index], 'PATCH');
			this.render();
		},

		deleteCompletedTasks: function () {
			this.getCompletedTasks().forEach(todo => {
				this.sendAjaxJson('api/delete', {uuid: todo.uuid}, 'DELETE');
			});
			this.todoItems = this.getActiveTasks();
			this.render();
		},

		toggleAll: function (e) {
			let toggleBool = $(e.target).prop('checked');

			this.todoItems.forEach(todo => {
				todo.isCompleted = toggleBool;
				this.sendAjaxJson('api/patch', todo, 'PATCH');
			})
			this.render();
		},

		filterTasks: function (e) {
			let route = $(e.target).prop('href').replace('http://localhost:8088/#/', '');
			if (route === 'active') {
				this.filter = 'active';
			}
			if (route === 'completed') {
				this.filter = 'completed'
			}
			if (route === 'all' && this.filter !== 'all') {
				this.filter = 'all';
			}
			if (route === 'all' && this.filter === 'all') {
				return;
			}
			this.render();
		},

		sendAjaxJson: function (url, data, method = 'GET') {
			$.ajax({
					url: url,
					method: method,
					data: JSON.stringify(data),
					contentType: 'application/json',
					processData: false,
				}
			)
		},

		emitFocusout: function (e) {
			if (e.which === 13) {
				e.target.blur();
			}
			if (e.which === 27) {
				$(e.target).data('abort', true).blur();
			}
		},

		getIndex: function (e) {
			const uuid = $(e.target).closest('li').data().id;
			let todos = this.todoItems
			let i = todos.length;

			while (i--) {
				if (todos[i].uuid === uuid) {
					return i;
				}
			}
		},

		switchEditingMode: function (e) {
			let $input = $(e.target).closest('li').addClass('editing').find('.edit');

			let tmpStr = $input.val();
			$input.val('');
			$input.val(tmpStr);
			$input.focus();
		},

		uuidv4: function () {
			return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
				(c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
			);
		}
	}

	App.run();
});
