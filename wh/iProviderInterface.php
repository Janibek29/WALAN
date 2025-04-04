<?php
/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
interface iProviderInterface {
    public function GetPost($get, $post);
    public function efs($ent);
    public function lst($ent, $fs, $uv);
    public function upd($ent, $uv, $fs, $rw, $dn);
    public function add($ent, $fs, $rw, $dn);
    public function del($uv);
}