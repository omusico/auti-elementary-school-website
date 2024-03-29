<?php
/**
 * 澳底國小網站程式
 *
 * LICENSE
 *
 * 本程式遵循GNU/GPL v3規範，詳情請見http://www.gnu.org/licenses/gpl.txt
 *
 * @copyright  2010 ottokang
 * @license    http://www.gnu.org/licenses/gpl.txt   GNU/GPL License 3
 */

/**
 * Album_AddController
 *
 * 新增相簿
 */
class Album_AddController extends Controller
{
    /**
     * 新增相簿
     */
    public function indexAction()
    {
        // 檢查權限
        $this->isAllowed('新增相簿', true);
        $album = new Model_Album();

        if ($this->isPost()) {
            if ($album->isValid()) {
                // 加入相簿建立日期
                $album->add();
                // 回到相簿首頁
                $this->redirect('album', $album->getMessage());
            } else {
                $this->view->message = $album->getMessage();
            }
        }

        $this->view->albumForm = $album->getForm();
        $this->render('index');
    }

    /**
     * 新增相片
     */
    public function photoAction()
    {
        $albumId = $this->getParam('albumId');

        // 檢查權限
        $this->isAllowed('新增相片', true);

        // 檢查相簿是否有效
        $album = new Model_Album();
        $albumRow = $album->getTable()->find($albumId)->current();
        if (!$albumRow) {
            $this->redirect('album', '相簿ID無效');
        }

        $photo = new Model_Photo();

        if ($this->isPost()) {
            if ($photo->isValid()) {
                // 加入相片資訊
                $photInfo['userId']     = Zend_Auth::getInstance()->getIdentity()->userId;
                $photInfo['albumId']    = $albumId;
                $photInfo['uploadDate'] = Date::getDate();
                $photInfo['uploadTime'] = Date::getTime();
                $photo->addData($photInfo);

                // 處理相片檔案
                set_time_limit(3600);
                $photoFiles = $photo->getForm()->uploadPhotos->getTransferAdapter()->getFileInfo();

                foreach ($photoFiles as &$photoFile) {
                    if ($photoFile['error'] == 0) {
                        if (strtolower(pathinfo($photoFile['name'], PATHINFO_EXTENSION)) == 'zip') {
                            //處理ZIP檔案
                            $zip = new ZipArchive();
                            if ($zip->open($photoFile['tmp_name']) == true) {
                                $tmpDir = ROOT_DIR . '/photos/tmp_' . Hash::generate() . '/';
                                for ($i = 0; $i < $zip->numFiles; $i++) {
                                    $entryName = $zip->getNameIndex($i);
                                    if (substr($entryName, -1) != '/' && strtolower(substr($entryName, -3)) == 'jpg') {
                                        $fileInfo = new FileInfo($entryName);
                                        $zip->renameIndex($i, Hash::generate() . '.jpg');
                                        $entries[] = $zip->getNameIndex($i);
                                        $photos[] = array('file'     => $tmpDir . $zip->getNameIndex($i),
                                                          'fileName' => $fileInfo->baseName);
                                        unset($fileInfo);
                                    }
                                }

                                mkdir($tmpDir);
                                $zip->extractTo($tmpDir, $entries);
                                $zip->close();
                                $tmpDirs[] = $tmpDir;
                            } else {
                                $message[] = '"' . $photoFile['name'] . '"';
                            }
                        } else {
                            $photos[] = array('file' => $photoFile['tmp_name'], 'fileName' => $photoFile['name']);
                        }
                    }
                }

                // 建立相片縮圖、寫入資料庫
                foreach ($photos as &$file) {
                    if ($photoHashFile = Image::resize($file['file'], array(1024, 768), array(200, 150))) {
                        $photoFile = new FileInfo($file['fileName']);
                        $photo->addData(array('fileName'      => $photoFile->fileName . '.' . strtolower($photoFile->extension),
                                              'photoHashFile' => $photoHashFile));
                        unset($photoFile);
                        $photo->add();
                    } else {
                        $message[] = '"' . $file['fileName'] . '"';
                    }
                }

                // 刪除暫存資料夾
                foreach ($tmpDirs as &$tmpDir) {
                    $tmpFiles = scandir($tmpDir);
                    unset($tmpFiles[0]);
                    unset($tmpFiles[1]);
                    foreach ($tmpFiles as $tmpFile) {
                        unlink($tmpDir . $tmpFile);
                    }
                    rmdir($tmpDir);
                }

                // 建立訊息
                if ($message) {
                    $message = '處理檔案' . implode('、', $message) . '發生錯誤，可能是檔案損毀或格式錯誤，請重新上傳';
                } else {
                    $message = '上傳完成';
                }

                // 回到相簿首頁
                $this->redirect("album/view/index/id/$albumId", $message);
            } else {
                $this->view->message = $photo->getMessage();
            }
        }

        $this->view->uploadForm = $photo->getForm();
        $this->render('photo');
    }
}
?>
