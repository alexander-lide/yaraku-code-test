<?php

namespace App\Http\Controllers;

use App\Http\Requests\DownloadListRequest;
use App\Models\Book;
use DOMDocument;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ListController extends Controller
{
    /**
     * Show the form for exporting and downloading lists.
     */
    public function index():View
    {
        return view('list/index');
    }

    /**
     * Export and download list as .csv or .xml
     */
    public function export(DownloadListRequest $request)
    {
        // Fetch books and/or their authors. If an author has not written a book they will not get on the list
        $rows = [];
        $includes = array_keys($request->include);

        // $bajsxml = $request->return

        $book = new Book();
        $items = $book->allBooksWithAuthors()->toArray();
        $file_name = implode('s_and_', $includes).'s';
        $exportedAuthors = [];

        foreach ($items as $key => $item)
        {
            // If we only show authors we only want to show them once in the list
            if ($file_name == 'authors')
            {
                if (in_array($item['author'], $exportedAuthors))
                {
                    continue;
                }
                $exportedAuthors[] = $item['author'];
            }
            foreach ($includes as $type)
            {
                $rows[$key][ucfirst($type)] = $item[$type];
            }
        }

        if (!empty($rows))
        {
            if ($request->type === 'csv')
            {
                return $this->exportCSV($rows, $file_name);
            }
            else
            {
                return $this->exportXML($rows, $file_name);
            }
        }
        else
        {
            Session::flash('error', 'Nothing to export!');

            return redirect('/list');
        }

    }

    /**
     * Export and return .csv list.
     */
    private function exportCSV( array $items, string $file_name )
    {
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$file_name.'.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        // Add headers for each column in the CSV download
        array_unshift($items, array_keys($items[0]));

        $callback = function() use ($items) 
        {
            $FH = fopen('php://output', 'w');
            foreach ($items as $row)
            { 
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export and return .xml list.
     */
    private function exportXML( array $rows, string $file_name)
    {
        $xml = new DOMDocument();

        $file_name = $file_name.'.xml';

        $rootNode = $xml->appendChild($xml->createElement("items"));

        foreach ($rows as $row)
        {
            if (!empty($row))
            {
                $itemNode = $rootNode->appendChild($xml->createElement('item'));
                foreach ($row as $key => $value)
                {
                    $itemNode->appendChild($xml->createElement($key, $value));
                }
            }
        }

        $xml->formatOutput = true;

        $xml->save($file_name);

        // return $xml;

        header('Content-Description: File Transfer');
        header('Content-Type: application/xml');
        header('Content-Disposition: attachment; filename=' . basename($file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_name));
        ob_clean();
        flush();
        readfile($file_name);
        exec('rm ' . $file_name);
    }
}
