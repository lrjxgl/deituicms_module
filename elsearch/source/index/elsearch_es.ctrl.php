<?php
use Elasticsearch\ClientBuilder;
class elsearch_esControl extends skymvc{
	public $es;
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		require 'vendor/autoload.php';
		$this->es = ClientBuilder::create()->build();
	}
	public function onDefault(){
		
		echo "success";
	}
	public function onCreateForum(){
		$client=$this->es;
		$params = [
			'index' => 'mod_forum',
			
			'body' => [
				'_source' => [
					'enabled' => true          //可选参数（可以减轻服务器压力）
				],
				'properties' => [
				 
					'contents' => [
						'type' => 'text', // 字符串型
						'analyzer'=>'ik_max_word', //ik_max_word 最细粒度拆分 ik_smart最粗粒度拆分
						'search_analyzer'=> 'ik_max_word'
					]
				 
				]
			]
		];
		
		$response = $client->indices()->putMapping($params);
		print_r($response);
		
	}
	public function onTest(){
		$client=$this->es;
		 
		$res=M("mod_forum")->getAll("
			select a.id,a.title,a.description,a.catid,a.view_num,a.comment_num,
				b.content 
			from ".table("mod_forum")." as a 
			left join ".table("mod_forum_data")." as b
			on a.id=b.id
			where a.status=1
					
		");
		foreach($res as $row){
			$params = [
			    'index' => 'mod_forum',
			    'id'    => $row["id"],
			    'body'  => $row
			];

			$response = $client->index($params);
		}
		
		echo "success";
	}
	public function onCreate(){
		$client=$this->es;
		$params = [
		    'index' => 'my_index',
		    'id'    => 'my_id',
		    'body'  => ['testField' => 'abc']
		];
		
		$response = $client->index($params);
		print_r($response);
	}
	
	public function onUpdate(){
		$client=$this->es;
		$params = [
		    'index' => 'my_index',
		    'id'    => 'my_id',
		    'body'  => [
		        'doc' => [
		            'new_field' => 'abc'
		        ]
		    ]
		];
		
		// Update doc at /my_index/_doc/my_id
		$response = $client->update($params);
		print_r($response);
	}
	
	public function onDelete(){
		$client=$this->es;
		$params = [
		    'index' => 'my_index',
		    'id'    => 'my_id'
		];
		
		$response = $client->delete($params);
		print_r($response);
		
	}
	public function onCreateIndex(){
		$client=$this->es;
		$params = [
		    'index' => 'mod_forum',
		    'body'  => [
		        'settings' => [
		            'number_of_shards' => 2,
		            'number_of_replicas' => 0
					
		        ]
		    ]
		];
		
		$response = $client->indices()->create($params);
		print_r($response);
		
	}
	public function onDeleteIndex(){
		$client=$this->es;
		$deleteParams = [
		    'index' => 'my_index'
		];
		$response = $client->indices()->delete($deleteParams);
		print_r($response);
		
	}
	public function onGet(){
		$client=$this->es;
		$params = [
		    'index' => 'my_index',
		    'id'    => 'my_id'
		];
		
		$response = $client->get($params);
		print_r($response);
	}
	
	public function onSearch(){
		$client=$this->es;
		$params = [
		    'index' => 'mod_forum',
		    'body'  => [
		        'query' => [
					
		            'match' => [
		                'title' => [
							"query"=>' 给美女摄影',
							
						]
		            ],
					
		        ],
				"sort"=>[
					"_score"=>[
						"order"=>"desc"
					]
				]
		    ]
		];
		
		$response = $client->search($params);
		print_r($response);
	}
	
	
}