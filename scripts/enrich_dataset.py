import json
import os
import re

raw_path = 'backend/database/data/oxford_raw.json'
output_path = 'backend/database/data/oxford_3000.json'

with open(raw_path, 'r', encoding='utf-8') as f:
    words = json.load(f)

# Curated high-frequency quality examples for early stages
CURATED_EXAMPLES = {
    "a, an": ("She eats an apple every morning.", "Cô ấy ăn một quả táo mỗi buổi sáng.", "A1"),
    "abandon": ("They had to abandon the car in the heavy snow.", "Họ đã phải bỏ lại chiếc xe trong tuyết dày.", "B2"),
    "ability": ("She has the ability to speak four foreign languages.", "Cô ấy có khả năng nói bốn thứ ngoại ngữ.", "A2"),
    "able": ("Will you be able to attend tomorrow's meeting?", "Bạn có thể tham dự cuộc họp ngày mai không?", "A2"),
    "about": ("We talked about our travel plans for this summer.", "Chúng tôi đã nói về kế hoạch du lịch cho mùa hè này.", "A1"),
    "above": ("The birds flew high above the green mountains.", "Đàn chim bay vút lên cao phía trên những ngọn núi xanh.", "A1"),
    "abroad": ("He decided to study abroad in the United States.", "Anh ấy quyết định đi du học ở Mỹ.", "A2"),
    "absence": ("Her frequent absence from school worried the teacher.", "Việc cô ấy vắng mặt thường xuyên khỏi lớp khiến cô giáo lo lắng.", "B2"),
    "absent": ("Why were you absent from the meeting yesterday?", "Tại sao bạn lại vắng mặt trong cuộc họp ngày hôm qua?", "A2"),
    "absolute": ("We have absolute confidence in his leadership skills.", "Chúng tôi có sự tin tưởng tuyệt đối vào khả năng lãnh đạo của anh ấy.", "B2"),
    "absolutely": ("You are absolutely right about this situation.", "Bạn hoàn toàn đúng về tình huống này.", "A2"),
    "absorb": ("Plants absorb carbon dioxide from the surrounding air.", "Cây cối hấp thụ khí carbon dioxide từ không khí xung quanh.", "B2"),
    "abuse": ("The government strictly bans the abuse of power.", "Chính phủ nghiêm cấm hành vi lạm dụng quyền lực.", "B2"),
    "academic": ("He has achieved high academic success at university.", "Anh ấy đã đạt được thành công học thuật cao tại trường đại học.", "B1"),
    "accent": ("She speaks English fluently with a clear American accent.", "Cô ấy nói tiếng Anh lưu loát với giọng chuẩn Mỹ rất rõ.", "B1"),
    "accept": ("I gladly accept your kind invitation to dinner.", "Tôi rất vui lòng nhận lời mời dùng bữa tối của bạn.", "A2"),
    "acceptable": ("This behavior is simply not acceptable in a professional workplace.", "Hành vi này hoàn toàn không thể chấp nhận được tại nơi làm việc chuyên nghiệp.", "B1"),
    "access": ("All students have free access to the digital library.", "Tất cả học sinh đều có quyền truy cập miễn phí vào thư viện số.", "B1"),
    "accident": ("Fortunately, nobody was hurt in the traffic accident.", "May mắn thay, không có ai bị thương trong vụ tai nạn giao thông.", "A2"),
    "accidental": ("The discovery of penicillin was an accidental breakthrough.", "Việc phát hiện ra penicillin là một bước đột phá tình cờ.", "B2"),
    "accidentally": ("I accidentally dropped my phone onto the floor.", "Tôi đã vô tình làm rơi điện thoại xuống sàn nhà.", "A2"),
    "accommodation": ("The hotel provides luxury accommodation with ocean views.", "Khách sạn cung cấp chỗ nghỉ sang trọng với tầm nhìn ra biển.", "B1"),
    "accompany": ("May I accompany you to the bus station?", "Tôi có thể đi cùng bạn đến bến xe buýt được không?", "B2"),
    "according to": ("According to the weather forecast, it will rain tomorrow.", "Theo như dự báo thời tiết, ngày mai trời sẽ mưa.", "A2"),
    "account": ("I would like to open a savings account today.", "Tôi muốn mở một tài khoản tiết kiệm vào ngày hôm nay.", "B1"),
    "accurate": ("The report gives an accurate description of the event.", "Bản báo cáo đưa ra mô tả chính xác về sự việc.", "B2"),
    "accurately": ("She answered all the difficult questions accurately.", "Cô ấy đã trả lời tất cả các câu hỏi khó một cách chính xác.", "B2"),
    "accuse": ("They accuse him of breaking the company rules.", "Họ cáo buộc anh ta vi phạm các quy định của công ty.", "B2"),
    "achieve": ("Work hard and you will achieve your dreams.", "Hãy làm việc chăm chỉ rồi bạn sẽ đạt được ước mơ của mình.", "A2"),
    "achievement": ("Winning first prize was her greatest achievement.", "Giành giải nhất là thành tựu vĩ đại nhất của cô ấy.", "B1"),
    "acid": ("Lemons contain citric acid which gives them a sour taste.", "Chanh chứa axit xitric mang lại cho chúng vị chua.", "B2"),
    "acknowledge": ("He refused to acknowledge his mistake in the project.", "Anh ấy từ chối thừa nhận sai lầm của mình trong dự án.", "B2"),
    "acquire": ("Children acquire language skills very quickly through play.", "Trẻ em tiếp thu kỹ năng ngôn ngữ rất nhanh qua việc vui chơi.", "B2"),
    "across": ("He swam bravely across the wide river.", "Anh ấy đã bơi dũng cảm băng qua dòng sông rộng.", "A1"),
    "act": ("Think carefully before you act in dangerous situations.", "Hãy suy nghĩ cẩn thận trước khi hành động trong những tình huống nguy hiểm.", "A2"),
    "action": ("We need to take immediate action to protect the environment.", "Chúng ta cần hành động ngay lập tức để bảo vệ môi trường.", "A2"),
    "active": ("She remains very active and exercises regularly every day.", "Cô ấy vẫn rất năng động và tập thể dục đều đặn mỗi ngày.", "A2"),
    "activity": ("Swimming is my favorite outdoor activity on weekends.", "Bơi lội là hoạt động ngoài trời yêu thích nhất của tôi vào cuối tuần.", "A1"),
    "actor": ("He is a very famous Hollywood actor in action movies.", "Anh ấy là một diễn viên Hollywood rất nổi tiếng trong các bộ phim hành động.", "A1"),
    "actress": ("She won an Oscar for best actress in a drama film.", "Cô ấy đã giành giải Oscar cho nữ diễn viên xuất sắc nhất trong một bộ phim chính kịch.", "A1"),
    "actual": ("The actual cost was much higher than we expected.", "Chi phí thực tế cao hơn nhiều so với dự tính của chúng tôi.", "B2"),
    "actually": ("I actually didn't realize what time it was.", "Thực sự tôi đã không nhận ra lúc đó là mấy giờ.", "A2"),
    "adapt": ("It takes time to adapt to a new working environment.", "Cần thời gian để thích nghi với một môi trường làm việc mới.", "B2"),
    "add": ("Please add some sugar and fresh milk to my coffee.", "Làm ơn cho thêm một chút đường và sữa tươi vào cà phê của tôi.", "A1"),
    "addition": ("In addition to studying, he also works a part-time job.", "Ngoài việc học, anh ấy còn làm một công việc bán thời gian.", "B1"),
    "additional": ("The course provides additional learning materials online.", "Khóa học cung cấp thêm các tài liệu học tập bổ sung trực tuyến.", "B1"),
    "address": ("Could you please write down your home address here?", "Bạn có thể vui lòng ghi địa chỉ nhà của mình vào đây không?", "A1"),
    "adequate": ("The room provides adequate space for our team meeting.", "Căn phòng cung cấp đủ không gian cho cuộc họp nhóm của chúng tôi.", "B2"),
    "adjust": ("You can adjust the volume using this knob.", "Bạn có thể điều chỉnh âm lượng bằng núm vặn này.", "B2"),
    "admiration": ("I have great admiration for her dedication to education.", "Tôi vô cùng khâm phục sự cống hiến của cô ấy cho giáo dục.", "B2"),
    "admire": ("I really admire people who never give up hope.", "Tôi thực sự ngưỡng mộ những người không bao giờ từ bỏ hy vọng.", "B1"),
    "admit": ("He admitted that he had made a careless mistake.", "Anh ấy thừa nhận rằng mình đã phạm phải một sai lầm bất cẩn.", "B1"),
    "adopt": ("They decided to adopt a puppy from the local shelter.", "Họ quyết định nhận nuôi một chú chó con từ trạm cứu hộ địa phương.", "B2"),
    "adult": ("Children must be accompanied by a responsible adult.", "Trẻ em phải có người lớn có trách nhiệm đi cùng.", "A1"),
    "advance": ("Technology continues to advance at an astonishing speed.", "Công nghệ tiếp tục tiến bộ với một tốc độ đáng kinh ngạc.", "B2"),
    "advanced": ("She is taking an advanced course in English literature.", "Cô ấy đang tham gia một khóa học nâng cao về văn học Anh.", "B1"),
    "advantage": ("Living in a big city offers many cultural advantages.", "Sống ở một thành phố lớn mang lại nhiều lợi thế về mặt văn hóa.", "A2"),
    "adventure": ("Traveling solo across Asia was an unforgettable adventure.", "Du lịch một mình xuyên châu Á là một chuyến phiêu lưu không thể nào quên.", "A2"),
    "advertise": ("Companies advertise their new products on social media.", "Các công ty quảng cáo sản phẩm mới của họ trên mạng xã hội.", "A2"),
    "advertisement": ("I saw an attractive job advertisement in the morning newspaper.", "Tôi đã thấy một mẩu quảng cáo tuyển dụng hấp dẫn trên tờ báo sáng.", "A2"),
    "advice": ("Can you give me some helpful advice on learning English?", "Bạn có thể cho tôi vài lời khuyên hữu ích về việc học tiếng Anh không?", "A1"),
    "advise": ("The doctor advised him to get more regular exercise.", "Bác sĩ đã khuyên anh ấy nên tập thể dục đều đặn hơn.", "B1"),
    "affair": ("Mind your own business and stay out of my private affairs.", "Hãy lo việc của bạn và đừng can thiệp vào chuyện riêng của tôi.", "B2"),
    "affect": ("Climate change will severely affect agricultural production.", "Biến đổi khí hậu sẽ ảnh hưởng nghiêm trọng đến sản xuất nông nghiệp.", "B1"),
    "affection": ("He felt a deep affection for his childhood hometown.", "Anh ấy cảm nhận được một tình cảm sâu đậm dành cho quê hương thời thơ ấu của mình.", "B2"),
    "afford": ("We cannot afford to buy an expensive luxury car right now.", "Chúng tôi không đủ khả năng tài chính để mua một chiếc xe hơi sang trọng đắt tiền ngay bây giờ.", "B1"),
    "afraid": ("Don't be afraid of making mistakes when practicing speaking.", "Đừng sợ mắc lỗi khi luyện nói.", "A1"),
    "after": ("We will go to the cinema after finishing our homework.", "Chúng ta sẽ đi xem phim sau khi hoàn thành bài tập về nhà.", "A1"),
    "afternoon": ("I usually drink a cup of green tea in the afternoon.", "Tôi thường uống một tách trà xanh vào buổi chiều.", "A1"),
    "afterward": ("Let's finish the test first and discuss it afterward.", "Hãy làm xong bài kiểm tra trước rồi thảo luận về nó sau.", "A2"),
    "again": ("Could you please repeat that important sentence again?", "Bạn có thể vui lòng nhắc lại câu quan trọng đó một lần nữa không?", "A1"),
    "against": ("They fought bravely against unfair treatment.", "Họ đã chiến đấu dũng cảm chống lại sự đối xử bất công.", "A2"),
    "age": ("He started playing the classical piano at the age of five.", "Anh ấy bắt đầu chơi đàn piano cổ điển từ năm lên năm tuổi.", "A1"),
    "aged": ("The organization helps aged people living alone in the city.", "Tổ chức này giúp đỡ những người cao tuổi sống một mình trong thành phố.", "B1"),
    "agency": ("She booked her dream vacation through a reputable travel agency.", "Cô ấy đã đặt kỳ nghỉ mơ ước của mình qua một công ty du lịch uy tín.", "B1"),
    "agent": ("The real estate agent showed us three beautiful apartments.", "Người đại lý bất động sản đã dẫn chúng tôi đi xem ba căn hộ xinh đẹp.", "B1"),
    "aggressive": ("He tends to become aggressive when he feels threatened.", "Anh ta có xu hướng trở nên hung hăng khi cảm thấy bị đe dọa.", "B2"),
    "ago": ("I moved to this vibrant city about two years ago.", "Tôi đã chuyển đến thành phố sôi động này khoảng hai năm trước.", "A1"),
    "agree": ("I completely agree with your proposal for this project.", "Tôi hoàn toàn đồng ý với đề xuất của bạn cho dự án này.", "A1"),
    "agreement": ("Both parties have successfully signed a long-term agreement.", "Cả hai bên đã ký kết thành công một thỏa thuận dài hạn.", "B1"),
    "ahead": ("Keep walking straight ahead until you see the station.", "Cứ tiếp tục đi thẳng về phía trước cho đến khi bạn nhìn thấy nhà ga.", "A2"),
    "aid": ("International organizations sent medical aid to the earthquake victims.", "Các tổ chức quốc tế đã gửi viện trợ y tế đến các nạn nhân vụ động đất.", "B2"),
    "aim": ("Our primary aim is to improve the quality of education.", "Mục tiêu hàng đầu của chúng tôi là nâng cao chất lượng giáo dục.", "A2"),
    "air": ("The fresh morning air in the mountains makes you feel energized.", "Không khí trong lành buổi sáng trên núi khiến bạn cảm thấy tràn đầy năng lượng.", "A1"),
    "aircraft": ("The new commercial aircraft can carry up to 400 passengers.", "Chiếc máy bay thương mại mới có thể chở tới 400 hành khách.", "B2"),
    "airport": ("Please arrive at the international airport two hours before departure.", "Vui lòng có mặt tại sân bay quốc tế trước giờ khởi hành hai tiếng.", "A1"),
    "alarm": ("My morning alarm clock rings loudly at six o'clock every day.", "Đồng hồ báo thức buổi sáng của tôi reo to vào lúc sáu giờ mỗi ngày.", "A2"),
    "alarming": ("The rapid rise in global temperatures is an alarming issue.", "Sự gia tăng nhanh chóng của nhiệt độ toàn cầu là một vấn đề đáng báo động.", "B2"),
    "alarmed": ("Neighbors were alarmed by the loud noise in the middle of the night.", "Hàng xóm đã hoảng hốt trước tiếng ồn lớn giữa đêm.", "B2"),
    "alcohol": ("Consuming too much alcohol can damage your overall health.", "Tiêu thụ quá nhiều rượu bia có thể gây tổn hại đến sức khỏe tổng thể của bạn.", "A2"),
    "alcoholic": ("They serve delicious non-alcoholic fruit drinks at the party.", "Họ phục vụ những món đồ uống trái cây không cồn rất ngon tại bữa tiệc.", "B1"),
    "alive": ("The doctor confirmed that the accident victim was still alive.", "Bác sĩ xác nhận rằng nạn nhân vụ tai nạn vẫn còn sống.", "A2"),
    "all": ("All students must wear school uniforms on Monday mornings.", "Tất cả học sinh phải mặc đồng phục vào các sáng thứ Hai.", "A1"),
    "all right": ("Don't worry about the spilled water, everything is all right.", "Đừng lo về chỗ nước bị đổ, mọi chuyện đều ổn cả.", "A1"),
    "allow": ("My parents allow me to hang out with friends on Saturdays.", "Bố mẹ cho phép tôi đi chơi với bạn bè vào các ngày thứ Bảy.", "A2"),
    "almost": ("We have almost completed the final draft of our report.", "Chúng tôi gần như đã hoàn thành bản dự thảo cuối cùng của báo cáo.", "A2"),
    "alone": ("She enjoys spending quiet time alone reading books in the library.", "Cô ấy thích dành thời gian yên tĩnh một mình để đọc sách trong thư viện.", "A1"),
    "along": ("We took a leisurely stroll along the scenic riverbank.", "Chúng tôi đã đi dạo thong thả dọc theo bờ sông thơ mộng.", "A2"),
    "alongside": ("A new bicycle lane was constructed alongside the highway.", "Một làn đường dành cho xe đạp mới đã được xây dựng dọc theo đường cao tốc.", "B2"),
    "aloud": ("The teacher asked him to read the English paragraph aloud.", "Giáo viên yêu cầu anh ấy đọc to đoạn văn tiếng Anh lên.", "B1"),
    "alphabet": ("Children learn the 26 letters of the English alphabet first.", "Trẻ em học 26 chữ cái trong bảng chữ cái tiếng Anh trước tiên.", "A1"),
    "alphabetical": ("The vocabulary terms are arranged in neat alphabetical order.", "Các thuật ngữ từ vựng được sắp xếp theo thứ tự bảng chữ cái gọn gàng.", "B1"),
    "already": ("I have already finished my homework so I can relax now.", "Tôi đã hoàn thành xong bài tập về nhà rồi nên bây giờ có thể thư giãn.", "A2"),
    "also": ("He speaks fluent French and also understands Italian well.", "Anh ấy nói tiếng Pháp lưu loát và cũng hiểu tiếng Ý rất tốt.", "A1"),
    "alter": ("You cannot alter the contract without mutual written consent.", "Bạn không thể thay đổi hợp đồng nếu không có sự đồng thuận bằng văn bản của cả hai bên.", "B2"),
    "alternative": ("Solar power is a clean alternative to fossil fuel energy.", "Năng lượng mặt trời là một giải pháp thay thế sạch cho nhiên liệu hóa thạch.", "B1"),
    "although": ("Although it rained heavily, we still enjoyed our picnic.", "Mặc dù trời mưa to, chúng tôi vẫn rất thích thú với chuyến dã ngoại của mình.", "A2"),
    "altogether": ("The new project cost three thousand dollars altogether.", "Dự án mới tiêu tốn tổng cộng ba nghìn đô la.", "B1"),
    "always": ("She always arrives at the office on time every morning.", "Cô ấy luôn luôn đến văn phòng đúng giờ vào mỗi buổi sáng.", "A1"),
    "amaze": ("The magician's incredible tricks continue to amaze the audience.", "Những màn ảo thuật đáng kinh ngạc của nhà ảo thuật tiếp tục làm kinh ngạc khán giả.", "B1"),
    "amazed": ("We were amazed at how quickly the children learned new words.", "Chúng tôi rất kinh ngạc trước việc lũ trẻ học từ mới nhanh như thế nào.", "B1"),
    "amazing": ("The view from the top of the mountain was absolutely amazing.", "Khung cảnh nhìn từ đỉnh núi thực sự tuyệt vời đáng kinh ngạc.", "A2"),
    "ambition": ("His biggest ambition is to become a successful software architect.", "Tham vọng lớn nhất của anh ấy là trở thành một kiến trúc sư phần mềm thành công.", "B1"),
    "ambulance": ("The ambulance arrived quickly at the scene of the accident.", "Xe cứu thương đã nhanh chóng đến hiện trường vụ tai nạn.", "A2"),
    "among": ("She felt very comfortable and happy among her close friends.", "Cô ấy cảm thấy rất thoải mái và hạnh phúc giữa những người bạn thân của mình.", "A2"),
    "amount": ("A small amount of daily practice leads to huge progress.", "Một lượng nhỏ thời gian luyện tập mỗi ngày sẽ tạo nên sự tiến bộ vượt bậc.", "A2"),
}

def clean_text(s):
    if not s:
        return ""
    return " ".join(s.split())

def generate_sentence(word, pos, mean):
    clean_w = word.strip()
    primary_meaning = mean.split(';')[0].split(',')[0].strip()
    
    # Check if we have curated
    w_key = clean_w.lower()
    if w_key in CURATED_EXAMPLES:
        return CURATED_EXAMPLES[w_key]
    
    # Generic intelligent template based on part of speech
    p_lower = pos.lower()
    if "động từ" in p_lower:
        en = f"They often {clean_w} when they face new challenges."
        vi = f"Họ thường {primary_meaning.lower()} khi đối mặt với những thử thách mới."
        lvl = "B1"
    elif "danh từ" in p_lower:
        en = f"Understanding the {clean_w} is essential for success."
        vi = f"Hiểu được {primary_meaning.lower()} là điều cần thiết để thành công."
        lvl = "A2"
    elif "tính từ" in p_lower:
        en = f"This is an especially {clean_w} topic to discuss."
        vi = f"Đây là một chủ đề đặc biệt {primary_meaning.lower()} để thảo luận."
        lvl = "B1"
    elif "trạng từ" in p_lower:
        en = f"She completed the task {clean_w} without hesitation."
        vi = f"Cô ấy hoàn thành nhiệm vụ một cách {primary_meaning.lower()} mà không do dự."
        lvl = "B1"
    else:
        en = f"We learned the word \"{clean_w}\" in our English lesson."
        vi = f"Chúng tôi đã học từ \"{clean_w}\" ({primary_meaning.lower()}) trong bài học tiếng Anh."
        lvl = "A2"
    
    return (en, vi, lvl)

enriched_words = []
for idx, item in enumerate(words):
    w = clean_text(item.get('word', ''))
    ph = clean_text(item.get('phonetic_us', ''))
    pos = clean_text(item.get('part_of_speech', ''))
    mean = clean_text(item.get('meaning_vi', ''))
    
    if not w:
        continue
    
    ex_en, ex_vi, cefr = generate_sentence(w, pos, mean)
    
    enriched_words.append({
        "id": idx + 1,
        "word": w,
        "phonetic_us": ph,
        "part_of_speech": pos,
        "meaning_vi": mean,
        "example_en": ex_en,
        "example_vi": ex_vi,
        "cefr_level": cefr,
        "audio_url": None
    })

print(f"Total enriched vocabulary entries: {len(enriched_words)}")

with open(output_path, 'w', encoding='utf-8') as f:
    json.dump(enriched_words, f, ensure_ascii=False, indent=2)

print(f"Successfully wrote {output_path}!")
