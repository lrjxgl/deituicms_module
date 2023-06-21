var App = new Vue({
	el: "#App",
	data: function() {
		return {
			oldWord: "",
			keyword: "",
			addrList: [],
			localAddress: "",
			aaBoxClass: "",
			searchList: "",
			nearList: "",
			lat: "",
			lng: "",
			city: "厦门",
		}
	},
	created: function() {
		var city = localStorage.getItem("wmo2o_city");
		if (city) {
			this.city = city;
		}
		this.userAddrList();
		this.localAddress = localStorage.getItem("wmo2o_localAddress");
		if (this.localAddress == '') {
			this.getLocation();
		}

		this.getNearList();

	},
	onShow: function() {
		var city = this.app.getCity();
		if (city) {
			this.city = city;
		}
	},
	methods: {
		gourl: function(url) {
			window.location=url
		},
		searchShow: function() {
			this.aaBoxClass = "flex-col";
		},
		search: function() {
			var that = this;
			var latlng = GPS.get();
			setTimeout(function() {
				if (that.oldWord != that.keyword) {
					that.oldWord = that.keyword;
					$.ajax({
						url: "/index.php?m=lbs&keyword=" + that.keyword,
						data: {
							lat: latlng.lat,
							lng: latlng.lng,
							city: that.city
						},
						dataType:"json",
						success: function(res) {
							that.searchList = res.data
						}
					})
				}
			}, 10)


		},
		userAddrList: function() {
			var that = this;
			$.ajax({
				url: "/index.php?m=user_address&a=my&ajax=1",
				unLogin: true,
				dataType:"json",
				success: function(res) {
					if (res.error) {
						return false;
					}
					that.addrList = res.data.list;
				}
			})
		},
		getNearList: function() {
			var that = this;
			var latlng = GPS.get();
			$.ajax({
				url: "/index.php?m=lbs&a=near&ajax=1",
				data: {
					lat: latlng.lat,
					lng: latlng.lng,
					city: that.city
				},
				dataType:"json",
				success: function(res) {
					that.nearList = res.data
				}
			})
		},

		setGpsLocal: function(item) {
			this.localAddress = item.address;
			localStorage.setItem("wmo2o_localAddress",item.address)
			GPS.set({lat:item.lat,lng:item.lng})
			window.location="/module.php?m=wmo2o"
		},
		setLocation: function(item) {
			this.localAddress = item.address;
			localStorage.setItem("wmo2o_localAddress",item.address)
			GPS.set({lat:item.lat,lng:item.lng})
			window.location="/module.php?m=wmo2o"
		},
		getLocation: function() {
			var that = this;
			navigator.geolocation.getCurrentPosition(function(res){
				that.lat = res.latitude;
				that.lng = res.longitude;
			},function(e){
				that.lat=28;
				that.lng=110
				console.log(e)
			})
			 
		}
	}
})
