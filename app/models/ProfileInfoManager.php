<?php


namespace app\models;


class ProfileInfoManager
{

    public function getProfileInfo()
    {
        return DbManager::requestSingle("SELECT * FROM web_info");
    }

    public function updateProfileInfo($values)
    {
        return DbManager::requestAffect("UPDATE web_info SET default_title=?,default_description=?,default_keywords=?,bio_title=?,bio_description=?,instagram_link=?,twitter_link=?,email=? WHERE id = 1",
            [$values["defaultTitle"], $values["defaultDescription"], $values["defaultKeywords"], $values["bioTitle"], $values["bioDescription"], $values["instagramLink"], $values["twitterLink"], $values["email"]]);
    }

    public function updateLogin($values)
    {
        return DbManager::requestAffect("UPDATE web_info SET login = ? WHERE id = 1", [password_hash($values["login"], PASSWORD_DEFAULT)]);
    }

    public function updateLandingPage($newFilename)
    {
        var_dump(DbManager::requestUnit("SELECT filename FROM landing_page_image WHERE id = 1;"));
        if ($filename=DbManager::requestUnit("SELECT filename FROM landing_page_image WHERE id = 1;")) {
            unlink("images/fullview/" . $filename);
            unlink("images/thumbnail/" . $filename);
            return DbManager::requestAffect("UPDATE landing_page_image SET filename = ? WHERE id = 1", [$newFilename]);
        }else{
            return DbManager::requestAffect("INSERT INTO landing_page_image(id, filename)  VALUES (1,?)", [$newFilename]);
        }
    }

    public function getLandingPageImage()
    {
        return DbManager::requestUnit("SELECT filename FROM landing_page_image WHERE id = 1");
    }
}