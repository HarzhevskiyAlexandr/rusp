<?php

namespace ItForFree\rusphp\Html\Table;


use ItForFree\rusphp\PHP\ArrayLib\Structure  as ArrayStructure;
use ItForFree\rusphp\PHP\ArrayLib\ArrCommon  as ArrCommon;

/**
 * 
 * Перестроит массив, элементы котрого содержат
 *  вложенные масивы атк, чтобы его было удобно выводить в html таблицу
 * определить colspan и rowspan для каждого элемента
 * 
 */
class ArrayRebuilder
{
    /**
     * Исходные данные, которые планируется выводить в виде html-таблицы
     * 
     * @var array 
     */
    protected $sourceArray = array();
    
    
    /**
     * Сюда из needleElementsAndSubarrays будут извелены "имена колонок"
     * таблицы, которую мы строим (с учётом вложенности).
     * Ведь по сути этот класс разворачивает массив со вложенностями в думерный (таблицу)
     * 
     * @var array
     */
    protected $columnNames = array();
    
    /**
     * Описывает какие именно поля надо извлечь для таблицы
     * -- массив с вложенными подмассивами, где перечислены имена ключей, которые следует извлекать из данных.
     * 
     * По факту описывает структуры одной сущности, которая будет выведена в виде "основной строки (в которй могут быть вложенности)",
     * такие "строки" в таблицы могут повторяться  сколько угодно раз,
     * но структура их описывается именно  этим массивом.
     * 
     * @var array
     */
    protected $needleElementsAndSubarrays = array();

    /**
     * Массив-результат (построенный с новой структурой для табличного вывода)
     * 
     * @var array
     */
    protected $result = array();
    
    
    public function __construct($sourceArray, $needleElementsAndSubarrays) {
        $this->sourceArray = $sourceArray;
        $this->needleElementsAndSubarrays = $needleElementsAndSubarrays;
        $this->getColumnNames($needleElementsAndSubarrays);
    }
    
    
        /**
     * Вернёт инфромацию о ячейка табилицы в фиксированном формате
     * 
     * @param string|int $content  то что будет отображено в ячейке таблицы
     * @param int $rowspan
     * @param false $emptyCell
     * @param int $colspan
     * @return array   информацию о ячейке в виде:
     * [
     *   'content' => '...', // соответствующий контент из $rowSourceArray
     *   'emptyCell' => false, // true если данных этой ячейки вообще нет в исходном массиве (при выводе в html такие вообще можно пропускать)
     *   'rowspan'  => 1,  // или реальное значение (из-за неоднородной длины вложенных массиво) 
     *                    //   -- одна из основных задач этого класса -- рассчитать это число.
     *   'colspan'  => 1, // просто для возможной совместимости 
     * ]
     * 
     */
    private static function getInfoForCellStructure($content, $rowspan = 1, $emptyCell = false, $colspan = 1)
    {
        return array(
            'content' => $content,
            'emptyCell' => $emptyCell, 
            'rowspan'  => $rowspan,                 
            'colspan' => $colspan
        );
    }
    
    
    
    /**
     * Построит для уже известных результатов html таблицу 
     * -- можно использовать в отладке
     * 
     * @return string
     */
    protected function getResultAsHTMLTable()
    {
        $result = $this->result;
        $html = '<table>';
        $columnNames = $this->columnNames;
         $html .= '<thead><tr>';  
        foreach ($columnNames as $columnName) {
            $html .= "<th> $columnName </th>";
        }
        $html .= '</tr></thead>';
        
        foreach ($result as $row) {
            $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<td>' . $cell['content'] . '</td>';
                }
            $html .= '</tr>';
           
        }
        $html .= '</table>';
        
        return $html;
    }
    
    
    /**
     * Выведет в виде html -- для тестирования
     */
    public function printResultHtmlTest()
    {
        echo $this->getResultAsHTMLTable();
    }
    
    
    
   
}

