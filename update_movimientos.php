<?php


	$pdo = $enlace = mysqli_connect("127.0.0.1", "root", "leo1979", "gestionfaena");
    

    $result = mysqli_query($pdo,   'SELECT s.id
FROM sp_st_mov_abst s
join sp_proc_fan_day pfd on pfd.id = s.id_proc_fan_day
join sp_proc_fan pf on pf.id = id_proc_fan
where pf.id = 14');
    while ($row = mysqli_fetch_array($result))
    {
    	$id = $row['id'];

    	mysqli_query($pdo, "UPDATE sp_st_mov_abst SET eliminado = 1 WHERE id = $id");
    }
?>

