<?php
/*
*PHP ELO 天梯比赛算法
*
*
*/
$p1 = 1200;
$p2 = 1300;


echo elo($p2,$p1,1,7,true)."\n";
echo elo($p1,$p2,0,1,False)."\n";



// $p1 = 1200;
// $p2 = 1300;
// // 玩家1  连 0.6 0.3 0.4 0.8 0.5
// $pd1_list = 0.6+0.3+0.4;//+0.8+0.5
// // 玩家2  连 0.6 0.3 0.4 0.8 0.5
// $pd2_list = 0.6+0.3+0.4;//+0.8+0.5

// echo elo($p2,$p1,4,$pd1_list)."\n";
// echo elo($p1,$p2,3,$pd2_list)."\n";

/*
*	$other_rank  对手的rank   int
*	$my_all_rank 我的rank     int
*	$victory 	 是否胜利     0失败   1胜利  int
*   $continuous  连胜次数     int
*   continuous_victory 连胜还是连败  bool类型  true 胜利  false 失败
*/
function elo($other_rank,$my_all_rank,$victory,$continuous,$continuous_victory){
	//连胜浮动比例
	global $continuous_num; 
	$continuous_num = 0.05;// 5%
	$my_rank = 0;
	if($continuous > 0){
		if($continuous_victory){
			if($victory){
				//连胜继续，使用原本分数计算加分 ps：多加
				$my_rank = $my_all_rank;
				$pd = 1/(1+pow(10,($other_rank - $my_all_rank)/400));
			}else{
				//连胜终结，使用隐藏分计算  ps:少减
				$pd = 1/(1+pow(10,($other_rank - $my_all_rank+$my_all_rank*$continuous*$continuous_num)/400));
			}
		}else{
			if($victory){
				//连败终结，使用隐藏分数计算加分 ps：少加
				$my_rank = $my_all_rank;
				$pd = 1/(1+pow(10,($other_rank - $my_all_rank+$my_all_rank*-$continuous*$continuous_num)/400));
				//echo $pd;
			}else{
				//连败继续，使用真实分计算  ps:多减
				$pd = 1/(1+pow(10,($other_rank - $my_all_rank)/400));
			}
		}
	}else{
		// 无连胜 正常计算
		$pd = 1/(1+pow(10,($other_rank - $my_all_rank)/400));	
	}
	return elo_Rank($my_all_rank,$pd,$victory,$my_rank);

}

// function elo($other_rank,$my_all_rank,$victory,$pd_list=0){
// 	$pd = 1/(1+pow(10,($other_rank - $my_all_rank)/400));
// 	if($pd_list){
// 		$pd += $pd_list;
// 	}
// 	return elo_Rank($my_all_rank,$pd,$victory,0);
// }
/*
* 天梯 分数结算
* return 		返回这把对战自己最终所得分数
* $my_all_rank  我的隐藏分
* $we 			预期胜率
* $victory      是否获胜
* $my_rank      我的原本分数
*/
function elo_Rank($my_all_rank,$we,$victory,$my_rank = 0){
	if($my_rank > 0){
		//是否使用隐藏分   如果有则不使用隐藏分
		$my_all_rank = $my_rank;
		//此处应有掌声
	}

	$rn = $my_all_rank + 32 *($victory - $we); //32 为每把最高上线积分
	return $rn;
}
