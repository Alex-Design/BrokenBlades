var app = new Vue({
	el: '#app',
	data: {
		 title: 'Welcome to Broken Blades!',
		 text: 'What would you like to do?',
		 responseMessage: '',
		 error: false,
		 
		 introScreen: true,

		 createCharacter: false,
		 accountId: 1,
		 characterId: 1,
		 characterName: '',
		 
		 selectChar: false,
		 characters: false,
		 
		 moveCharacter: false,
		 locationData: null,
		 moveNorth: false,
		 moveEast: false,
		 moveSouth: false,
		 moveWest: false,
		 
		 droppedLootData: false,
		 dropedLootItems: false,
		 
		 inventoryItems: false,
		 inventoryData: false,
	},
	methods: {
			
			clearAllTemplates: function() {
					this.introScreen = false;
					this.error = false;
					this.responseMessage = false;
					this.createCharacter = false;
					this.moveCharacter = false;
					this.inventory = false;
					this.selectChar = false;
			},
			
			switchTemplate: function(chosenTemplate) {
					switch (chosenTemplate) {
							case 'createNewCharacter': 
									this.clearAllTemplates();
									this.createCharacter = true;
									this.responseMessage = 'Please name your new character.';
									break;
							case 'saveCharacter': 
									this.clearAllTemplates();
									this.introScreen = true;
									break;
							case 'playGame':
									this.clearAllTemplates();
									this.moveCharacter = true;
									this.inventory = true;
									break;
							case 'moveCharacter':
									this.clearAllTemplates();
									break;
							case 'selectCharacter':
									this.clearAllTemplates();
									this.selectChar = true;
									break;
					}
			},
			
			createNewCharacter: function() {
					this.switchTemplate('createNewCharacter');
			},
			
			selectCharacter: function() {
					
					var data = new FormData();
					data.append("accountId", this.accountId);
					
					axios.post('/handle/select/character', data)
					 .then(function (response) {
							if (response.data.success == false) {
									alert(response.data.message);
							} else {
									this.characters = response.data.characters;
									
									this.switchTemplate('selectCharacter');
							}
					}.bind(this))
					.catch(function (error) {
							this.error = error;
							console.log(error); // temp
					}.bind(this));
			},
			
			chooseCharacter(characterId, characterName) {
					this.characterId = characterId;
					this.characterName = characterName;
					
					// now go to play game
					this.playGame();
			},
			
			saveCharacter: function() {
					
					this.switchTemplate('saveCharacter');
					
					// AXIOS does not allow normal object in POST
					var data = new FormData();
					data.append("accountId", this.accountId);
					
					axios.post('/handle/create/character', data) // get and send account ID/ideally reference
					 .then(function (response) {
							if (response.data.success == false) {
									alert(response.data.message);
							} else {
									this.responseMessage = response.data.message;
									this.characterId = response.data.characterId;
									
									data.append("characterId", this.characterId);
									data.append("name", this.characterName);
									
									axios.post('/handle/update/character', data)
									.then(function (response) {
											if (response.data.success == false) {
												 alert(response.data.message);
											} else {
												 this.responseMessage = response.data.message;
												 this.createCharacter = false;
											}
									}.bind(this))
									.catch(function (error) {
										 this.error = error;
										 console.log(error); // temp
									}.bind(this));
							}
					}.bind(this))
					.catch(function (error) {
							this.error = true;
							this.error = error;
							console.log(error); // temp
					}.bind(this));
			},
			
			playGame: function() {
					this.switchTemplate('playGame');
					this.getCharacterLocation();
					this.getCharacterInventory();
			},
			
			getCharacterLocation: function() {
					
					// AXIOS does not allow normal object in POST
					var data = new FormData();
					data.append("accountId", this.accountId);
					data.append("characterId", this.characterId);

					axios.post('/handle/getCharacter/location', data)
							 .then(function (response) {
									if (response.data.success == false) {
										 alert(response.data.message);
									} else {
											this.responseMessage = response.data.message;

											this.moveNorth = response.data.moveNorth;
											this.moveEast = response.data.moveEast;
											this.moveSouth = response.data.moveSouth;
											this.moveWest = response.data.moveWest;

											this.locationName = response.data.locationName;
											this.locationDescription = response.data.locationDescription;
											this.charactersPresent = response.data.allPlayersInLocation;

											this.droppedLootItems = response.data.droppedLootItems;

											this.locationData = true;
											this.droppedLootData = true;
									}
							}.bind(this))
							.catch(function (error) {
									this.error = error;
									console.log(error); // temp
							}.bind(this));
			},
			
			moveCharacterAround: function(moveTo) {
					
					// AXIOS does not allow normal object in POST
					var data = new FormData();
					data.append("accountId", this.accountId);
					data.append("characterId", this.characterId);
					data.append("moveTo", moveTo);
					
					axios.post('/handle/moveCharacter/location', data)
							 .then(function (response) {
									if (response.data.success == false) {
										 alert(response.data.message);
									} else {
											this.responseMessage = response.data.message;

											this.moveNorth = response.data.moveNorth;
											this.moveEast = response.data.moveEast;
											this.moveSouth = response.data.moveSouth;
											this.moveWest = response.data.moveWest;

											this.locationName = response.data.locationName;
											this.locationDescription = response.data.locationDescription;
											this.charactersPresent = response.data.allPlayersInLocation;
									}
							}.bind(this))
							.catch(function (error) {
									this.error = error;
									console.log(error); // temp
							}.bind(this));
			},
			
			moveCharacterNorth: function() {
					this.moveCharacterAround(this.moveNorth);
			},
			
			moveCharacterEast: function() {
					this.moveCharacterAround(this.moveEast);
			},
			
			moveCharacterSouth: function() {
					this.moveCharacterAround(this.moveSouth);
			},
			
			moveCharacterWest: function() {
					this.moveCharacterAround(this.moveWest);
			},
			
			getCharacterInventory: function() {
					
					// AXIOS does not allow normal object in POST
					var data = new FormData();
					data.append("accountId", this.accountId);
					data.append("characterId", this.characterId);

					axios.post('/handle/getCharacter/inventory', data)
							 .then(function (response) {
									if (response.data.success == false) {
											this.inventoryItems = false;
											this.inventoryData = true;
									} else {
											this.responseMessage = response.data.message;

											this.inventoryItems = response.data.characterInventoryItems;
											this.inventoryData = true;
									}
							}.bind(this))
							.catch(function (error) {
									this.error = true;
									this.error = error;
									console.log(error); // temp
							}.bind(this));
			},
			
			pickUpItem: function(itemId) {
					// AXIOS does not allow normal object in POST
					var data = new FormData();
					data.append("accountId", this.accountId);
					data.append("characterId", this.characterId);
					data.append("itemId", itemId);

					axios.post('/handle/addCharacter/item', data)
							 .then(function (response) {
									if (response.data.success == false) {
										 alert(response.data.message);
									} else {
											this.responseMessage = response.data.message;
									}
							}.bind(this))
							.catch(function (error) {
									this.error = true;
									this.error = error;
									console.log(error); // temp
							}.bind(this));
							
					this.getCharacterInventory(); // refresh inventory from server
			}
	}
});