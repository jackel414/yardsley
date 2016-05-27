App = Ember.Application.create();

App.ApplicationAdapter = DS.ActiveModelAdapter.extend({
  namespace: 'api',
  headers: {
    'Authorization': 'Token token=EMBER-TOKEN'
  }
});

App.Router.map(function() {
  this.route('home', { path: '/' });
  this.resource('users', function() {
    this.resource('user', { path: ':user_id' }, function() {
      this.route('edit');
    });
    this.route('new');
  });
});

App.HomeRoute = Ember.Route.extend({
  model: function() {
    return Ember.RSVP.hash({
      users: this.store.findAll('user'),
    });
  },
  setupController: function(controller, model) {
    controller.set('users', model.users);
  }
});
App.HomeController = Ember.ArrayController.extend({
});

App.UsersNewRoute = Ember.Route.extend({
  model: function() {
    return Ember.RSVP.hash({
      user: this.store.createRecord('user')
    });
  },
  setupController: function(controller, model) {
    controller.set('model', model.user);
  },
  actions: {
    willTransition: function(transition) {
      if(this.currentModel.user.get('isNew')) {
        if(confirm("Are you sure you want to abandon progress?")) {
          this.currentModel.user.destroyRecord();
        } else {
          transition.abort();
        }
      }
    }
  }
});
App.UsersNewController = Ember.Controller.extend({
  actions: {
    createUser: function() {
      var controller = this;
      this.get('model').save().then(function() {
        controller.transitionToRoute('home');
      });
    }
  }
});

//model attributes
App.User = DS.Model.extend({
  first_name: DS.attr(),
  last_name: DS.attr(),
  email: DS.attr(),
  products: DS.hasMany('product', { async: true })
});

App.Product = DS.Model.extend({
  name: DS.attr(),
  user: DS.belongsTo('user', { async: true })
});
