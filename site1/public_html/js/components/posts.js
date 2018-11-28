Vue.use(axios);
Vue.use(Croppa);
Vue.use(VueMce);

new Vue({
	el : "#app",
	data : {
		//Validation messages
		validationMessage : '',
		//For Image Cropping
		myCroppa : {},
		//Post Content
		post_id : 0,
		title : '',
		content : '',
		user_id : 0,
		is_published : 1,
		tinymceConfig : {
			theme : 'modern',
			menubar : false,
			statusbar : false,
			plugins : 'autolink lists link',
			toolbar1 : 'bold italic strikethrough underline link numlist bullist outdent indent',
		},
	},

	//Executes when th view component has loaded
	created : function() {
		
		let that = this;
		
		//Load A Request, if present
		var post_id = $("#post_id").val();

		if (post_id) {

			this.post_id = post_id;

			axios.get('/api/findPost/' + post_id).then(function(response) {
				that.title = response.data.title;
				that.content = response.data.content;
				that.is_published = response.data.is_published;

			}).catch(function(e) {
				console.log('Error');
				console.log(e);
			});

		}
		
	},
	methods : {
		//Create A New Post
		createPost : function() {
			
			//Get the data needed to created the post
			let data = {
				title : this.title,
				content : this.content,
				is_published : this.is_published,
			};

			//save the scope
			let that = this;

			//First call gets the session info
			axios.get('/api/session').then(function(response) {
				
				let user_id = response['data']['user_id'];

				if (user_id) {

					//Attach current user
					data['user_id'] = user_id;

					//Attempt to create the post
					axios.post('/api/createPost', data).then(function(res) {
						
						window.location = '/posts/view/' + res.data.post_id;
						
					}).catch(function(err) {

						if (err && err.response) {
							that.validationMessage = '<div class="alert alert-danger" role="alert">' + err.response.data + '</div>';

							setTimeout(function() {
								that.validationMessage = '';
							}, 6000);
						}
					});

				}
			}).catch(function(e) {
				console.log('Error');
				console.log(e);
			});

		},
		
		//Updates The Current post
		updatePost : function() {

			//Get only the fields needs
			let data = {
				title : this.title,
				content : this.content,
				is_published : this.is_published,
				post_id : this.post_id
			};

			//Save the scpoe
			let that = this;

			axios.get('/api/session').then(function(response) {
				
				let user_id = response['data']['user_id'];

				//Loggedin User Found
				if (user_id) {

					//Update this post
					axios.put('/api/updatePost/' + that.post_id, data).then(function(res) {
						
						window.location = '/posts/view/' + res.data.post_id;
						
					}).catch(function(err) {

						if (err && err.response) {
							
							that.validationMessage = '<div class="alert alert-danger" role="alert">' + err.response.data + '</div>';

							setTimeout(function() {
								that.validationMessage = '';
							}, 6000);
						}
					});

				}
			}).catch(function(e) {
				console.log('Error');
				console.log(e);
			});

		},
	}
}); 