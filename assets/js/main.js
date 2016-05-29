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
      this.route('products');
    });
    this.route('new');
  });
  this.resource('products', function() {
    this.resource('product', { path: ':product_id' }, function() {
      this.route('edit');
    });
    this.route('new');
  });
});

App.UsersIndexRoute = Ember.Route.extend({
  model: function() {
    return Ember.RSVP.hash({
      users: this.store.findAll('user'),
    });
  },
  setupController: function(controller, model) {
    controller.set('users', model.users);
  }
});
App.UsersIndexController = Ember.ArrayController.extend({
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
        controller.transitionToRoute('users');
      });
    }
  }
});

App.UserProductsRoute = Ember.Route.extend({
  model: function() {
    return Ember.RSVP.hash({
      user: this.store.find('user', this.modelFor('user').get('id')),
    });
  },
  setupController: function(controller, model) {
    controller.set('user', model.user);
  }
});
App.UserProductsController = Ember.ArrayController.extend({
});


App.ProductsIndexRoute = Ember.Route.extend({
  model: function() {
    return Ember.RSVP.hash({
      products: this.store.findAll('product'),
    });
  },
  setupController: function(controller, model) {
    controller.set('products', model.products);
  }
});
App.ProductsIndexController = Ember.ArrayController.extend({
});

App.ProductsNewRoute = Ember.Route.extend({
  model: function() {
    return Ember.RSVP.hash({
      product: this.store.createRecord('product'),
      users: this.store.findAll('user')
    });
  },
  setupController: function(controller, model) {
    controller.set('model', model.product);
    controller.set('users', model.users);
  },
  actions: {
    willTransition: function(transition) {
      if(this.currentModel.product.get('isNew')) {
        if(confirm("Are you sure you want to abandon progress?")) {
          this.currentModel.product.destroyRecord();
        } else {
          transition.abort();
        }
      }
    }
  }
});
App.ProductsNewController = Ember.Controller.extend({
  categories: ["Furnature", "Kitchen Ware", "Electronics"],
  actions: {
    createProduct: function() {
      var controller = this;
      this.get('model').save().then(function() {
        controller.transitionToRoute('products');
      });
    }
  }
});


//model attributes
App.User = DS.Model.extend({
  name: DS.attr(),
  email: DS.attr(),
  created_at: DS.attr(),
  updated_at: DS.attr(),
  products: DS.hasMany('product', { async: true })
});

App.Product = DS.Model.extend({
  name: DS.attr(),
  description: DS.attr(),
  photo: DS.attr(),
  category: DS.attr(),
  brand: DS.attr(),
  original_price: DS.attr(),
  asking_price: DS.attr(),
  created_at: DS.attr(),
  updated_at: DS.attr(),
  user: DS.belongsTo('user', { async: true })
});
